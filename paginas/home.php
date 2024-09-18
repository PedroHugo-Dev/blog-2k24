<?php
include_once('../includes/header.php');
include_once('../config/conexao.php');

// Sanitização de entrada
$acao = filter_var(isset($_GET['acao']) ? $_GET['acao'] : 'bemvindo', FILTER_SANITIZE_STRING);

// Definir caminhos em variáveis
$paginas = [
    'bemvindo' => 'conteudo/cadastro_contato.php',
    'editar' => 'conteudo/update_contato.php',
    'perfil' => 'conteudo/perfil.php',
];

// Verificar se a ação existe no array, caso contrário, usar a página padrão
$pagina_incluir = isset($paginas[$acao]) ? $paginas[$acao] : $paginas['bemvindo'];

// Incluir a página correspondente
if ($acao === 'bemvindo') {
    // Obtém o ID do usuário logado
    $usuarioLogado = $_SESSION['loginUser'];

    try {
        // Consulta os tópicos em que o usuário participa
        $selectTopicos = "
            SELECT t.id_topico, t.nome as topico_nome
            FROM participa p
            JOIN topico t ON p.id_topico = t.id_topico
            WHERE p.id_user = (
                SELECT id_user
                FROM tb_user
                WHERE email_user = :emailUserLogado
            )
        ";

        $resultadoTopicos = $conect->prepare($selectTopicos);
        $resultadoTopicos->bindParam(':emailUserLogado', $usuarioLogado, PDO::PARAM_STR);
        $resultadoTopicos->execute();
        $topicosParticipa = $resultadoTopicos->fetchAll(PDO::FETCH_ASSOC);

        $topicosIds = array_column($topicosParticipa, 'id_topico');

        if ($topicosIds) {
            // Se o usuário participa de tópicos, buscar postagens desses tópicos
            $in = str_repeat('?,', count($topicosIds) - 1) . '?';
            $selectPostagens = "
                SELECT p.*, t.nome as topico_nome
                FROM post p
                JOIN topico t ON p.id_topico = t.id_topico
                WHERE p.id_topico IN ($in)
                ORDER BY RAND()
                LIMIT 5
            ";

            $resultadoPostagens = $conect->prepare($selectPostagens);
            $resultadoPostagens->execute($topicosIds);
            $postagens = $resultadoPostagens->fetchAll(PDO::FETCH_ASSOC);

        } else {
            // Se o usuário não participa de tópicos, buscar postagens de tópicos aleatórios
            $selectPostagensAleatorias = "
                SELECT p.*, t.nome as topico_nome
                FROM post p
                JOIN topico t ON p.id_topico = t.id_topico
                ORDER BY RAND()
                LIMIT 5
            ";

            $resultadoPostagensAleatorias = $conect->prepare($selectPostagensAleatorias);
            $resultadoPostagensAleatorias->execute();
            $postagens = $resultadoPostagensAleatorias->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        error_log("ERRO AO BUSCAR POSTAGENS: " . $e->getMessage());
        $postagens = []; // Definido como vazio para evitar erros na exibição
    }
} else {
    include_once($pagina_incluir);
}
?>

<style>
/* Estilizando o card e posts */
.card-body {
   padding: 20px;
}

/* Estilizando cada artigo */
.posts article {
   background-color: #ffffff;
   border-radius: 8px;
   box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
   padding: 20px;
   margin-bottom: 30px; /* Adiciona espaçamento entre artigos */
}

.posts h2 {
   font-size: 22px;
   margin-top: 0;
   color: #333;
   font-weight: 600;
}

.posts p {
   line-height: 1.8;
   color: #495057;
   font-size: 16px;
}

.posts small {
   display: block;
   margin-top: 10px;
   color: #6c757d;
   font-size: 14px;
}

/* Adicionando espaçamento extra para o último artigo */
.posts article:last-of-type {
   margin-bottom: 0;
}

/* Melhorando a responsividade */
@media (max-width: 992px) {
   .content-header h1 {
       font-size: 24px;
   }

   .card-header {
       font-size: 18px;
   }

   .posts h2 {
       font-size: 20px;
   }

   hr {
       border: 1px solid #00ff00; /* Green color */
   }
}

@media (max-width: 768px) {
   .card-body {
       padding: 15px;
   }

   .content-header h1 {
       font-size: 20px;
   }

   .card-header {
       font-size: 16px;
   }

   .posts h2 {
       font-size: 18px;
   }

   .posts p {
       font-size: 14px;
   }

   .posts small {
       font-size: 12px;
   }
}
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Blog - Home</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">

              <!-- /.card-header -->
              <div class="card-body">
                <div id="posts" class="posts">
                    <!-- Os posts iniciais serão inseridos aqui -->
                    <?php if ($postagens): ?>
                            <?php foreach ($postagens as $post): ?>
                                <article class="post">
                                    <h2><?php echo htmlspecialchars($post['titulo']); ?></h2>
                                    <p><?php echo nl2br(htmlspecialchars($post['corpo'])); ?></p>
                                    <p><small>Postado em: <?php echo $post['data_criacao']; ?></small></p>
                                    <p><small>De: <?php echo $post['topico_nome']; ?></small></p>
                                    
                                    <!-- Exibir comentários -->
                                    <div class="comentarios">
                                        <?php
                                        // Obter e exibir comentários para o post
                                        $idPost = $post['id_post'];
                                        $selectComentarios = "
                                            SELECT c.*, u.nome_user
                                            FROM comentario c
                                            JOIN tb_user u ON c.id_user = u.id_user
                                            WHERE c.id_post = :idPost
                                            ORDER BY c.data_criacao ASC
                                        ";
                                        
                                        $resultadoComentarios = $conect->prepare($selectComentarios);
                                        $resultadoComentarios->bindParam(':idPost', $idPost, PDO::PARAM_INT);
                                        $resultadoComentarios->execute();
                                        $comentarios = $resultadoComentarios->fetchAll(PDO::FETCH_ASSOC);
                                        ?>

                                        <?php if ($comentarios): ?>
                                            <?php foreach ($comentarios as $comentario): ?>
                                                <div class="comentario">
                                                    <p><strong><?php echo htmlspecialchars($comentario['nome_user']); ?>:</strong></p>
                                                    <p><?php echo nl2br(htmlspecialchars($comentario['corpo'])); ?></p>
                                                    <p><small>Comentário postado em: <?php echo $comentario['data_criacao']; ?></small></p>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <p>Nenhum comentário encontrado.</p>
                                        <?php endif; ?>
                                    </div>
                                    <!-- Formulário para adicionar comentários -->
                                                <form method="post" action="adicionar_comentario.php">
                                                    <input type="hidden" name="id_post" value="<?php echo $post['id_post']; ?>">
                                                    <input type="hidden" name="id_topico" value="<?php echo $post['id_topico']; ?>">
                                                    <div class="form-group">
                                                        <label for="comentario">Adicionar um comentário:</label>
                                                        <textarea id="comentario" name="texto_comentario" class="form-control" rows="3" required></textarea>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Comentar</button>
                                                </form>

                                    <hr style="border: 1px solid #ffc107;">
                                </article>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Nenhum post encontrado.</p>
                        <?php endif; ?>
                        
                </div>
                <div id="loading" style="display: none;"></div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!--/.col (right) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<?php include_once('../includes/footer.php'); ?>

<script>
    let offset = 5; // Começa após os 5 posts iniciais
    let loading = false;

    // Função para carregar posts
    function carregarPosts() {
        if (loading) return; // Evita múltiplas requisições simultâneas
        loading = true;
        document.getElementById('loading').style.display = 'block';

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'carregar_posts.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                const posts = JSON.parse(xhr.responseText);
                if (posts.length > 0) {
                    const container = document.getElementById('posts-container');
                    posts.forEach(post => {
                        const postElement = document.createElement('article');
                        postElement.classList.add('post');
                        postElement.innerHTML = `
                            <h2>${post.titulo}</h2>
                            <p>${post.corpo}</p>
                            <p><small>Postado em: ${post.data_criacao}</small></p>
                            <p><small>De: ${post.topico_nome}</small></p>
                        `;
                        container.appendChild(postElement);
                        container.appendChild(document.createElement('hr')).style.border = '1px solid #ffc107';
                    });
                    offset += posts.length; // Atualiza o offset
                } else {
                    window.removeEventListener('scroll', onScroll); // Remove o listener se não houver mais posts
                }
            }
            document.getElementById('loading').style.display = 'none';
            loading = false;
        };
        xhr.send(`offset=${offset}`);
    }

    // Função para verificar se o usuário chegou ao fim da página
    function onScroll() {
        if (window.innerHeight + window.scrollY >= document.documentElement.scrollHeight) {
            carregarPosts();
        }
    }

    // Adiciona o evento de rolagem
    window.addEventListener('scroll', onScroll);

    // Carrega posts iniciais
    carregarPosts();
</script>
