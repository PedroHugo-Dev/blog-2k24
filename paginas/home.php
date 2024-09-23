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

// Lógica para deletar post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'])) {
    if ($_POST['acao'] === 'deletar' && isset($_POST['id_post'])) {
        $id_post = filter_input(INPUT_POST, 'id_post', FILTER_VALIDATE_INT);
        if ($id_post) {
            try {
                $deletePost = "DELETE FROM post WHERE id_post = :id_post";
                $stmt = $conect->prepare($deletePost);
                $stmt->bindParam(':id_post', $id_post, PDO::PARAM_INT);
                $stmt->execute();
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } catch (PDOException $e) {
                error_log("ERRO AO DELETAR POST: " . $e->getMessage());
            }
        }
    }
}

// Continue com a lógica existente
if ($acao === 'bemvindo') {
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
        $postagens = [];
    }
} elseif ($acao === 'perfil') {
    // Não exibe nada relacionado à home no perfil
    include_once($pagina_incluir);
    exit();
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
   margin-bottom: 30px;
   transition: box-shadow 0.3s ease;
}

.posts article:hover {
   box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

/* Títulos dos posts */
.posts h2 {
   font-size: 22px;
   margin-top: 0;
   color: #333;
   font-weight: 600;
}

/* Corpo dos posts */
.posts p {
   line-height: 1.8;
   color: #495057;
   font-size: 16px;
   margin: 10px 0;
}

/* Informações adicionais */
.posts small {
   display: block;
   margin-top: 10px;
   color: #6c757d;
   font-size: 14px;
}

/* Estilizando os comentários */
.comentarios {
   background-color: #f8f9fa;
   border-left: 4px solid #ffc107;
   padding: 10px 15px;
   margin-top: 15px;
   border-radius: 5px;
}

.comentario {
   margin-bottom: 10px;
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
       border: 1px solid #ffc107;
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

/* Estilizando o container dos posts */
.posts {
    display: flex;
    flex-direction: column;
    gap: 20px;
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
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="posts" id="posts">
                            <?php if (isset($postagens)): ?>
                                <?php foreach ($postagens as $post): ?>
                                    <article>
                                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                            <div style="flex-grow: 1;">
                                                <h2><?php echo htmlspecialchars($post['titulo']); ?></h2>
                                                <p><?php echo nl2br(htmlspecialchars($post['corpo'])); ?></p>
                                                <p><small>Postado em: <?php echo $post['data_criacao']; ?></small></p>
                                                <p><small>De: <?php echo $post['topico_nome']; ?></small></p>
                                            </div>
                                            <div>
                                                <button class="btn btn-secondary" onclick="toggleComentarios(<?php echo $post['id_post']; ?>)" style="margin-left: 10px;">
                                                    <i class="fas fa-comments"></i>
                                                </button>
                                                <button class="btn btn-secondary" onclick="openEditForm(<?php echo $post['id_post']; ?>)" style="margin-left: 10px;">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </button>
                                                <form method="post" action="" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja deletar?');">
                                                    <input type="hidden" name="acao" value="deletar">
                                                    <input type="hidden" name="id_post" value="<?php echo $post['id_post']; ?>">
                                                    <button type="submit" class="btn btn-danger" style="margin-left: 10px;">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        <div id="edit-form-<?php echo $post['id_post']; ?>" style="display: none; margin-top: 10px;">
                                            <form method="post" onsubmit="return updatePost(<?php echo $post['id_post']; ?>, this);">
                                                <input type="hidden" name="acao" value="atualizar">
                                                <input type="hidden" name="id_post" value="<?php echo $post['id_post']; ?>">
                                                <div class="form-group">
                                                    <label for="titulo">Título:</label>
                                                    <input type="text" name="titulo" class="form-control" value="<?php echo htmlspecialchars($post['titulo']); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="corpo">Conteúdo:</label>
                                                    <textarea name="corpo" required class="form-control"><?php echo htmlspecialchars($post['corpo']); ?></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Atualizar</button>
                                                <button type="button" class="btn btn-secondary" onclick="cancelEdit(<?php echo $post['id_post']; ?>)" style="margin-left: 10px;">Cancelar</button>
                                            </form>
                                        </div>

                                        <div class="comentarios" id="comentarios-<?php echo $post['id_post']; ?>" style="display: none;">
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

                                            <form method="post" action="adicionar_comentario.php">
                                                <input type="hidden" name="id_post" value="<?php echo $post['id_post']; ?>">
                                                <input type="hidden" name="id_topico" value="<?php echo $post['id_topico']; ?>">
                                                <div class="form-group">
                                                    <label for="comentario">Adicionar um comentário:</label>
                                                    <textarea id="comentario" name="texto_comentario" class="form-control" rows="3" required></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Comentar</button>
                                            </form>
                                        </div>
                                        <hr style="border: 1px solid #ffc107;">
                                    </article>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>Nenhum post encontrado.</p>
                            <?php endif; ?>
                        </div>
                        <div id="loading" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
</div>

<script>
    function toggleComentarios(postId) {
        const comentariosDiv = document.getElementById(`comentarios-${postId}`);
        comentariosDiv.style.display = comentariosDiv.style.display === 'none' ? 'block' : 'none';
    }

    function openEditForm(postId) {
        const editForm = document.getElementById(`edit-form-${postId}`);
        editForm.style.display = editForm.style.display === 'none' ? 'block' : 'none';
    }

    function cancelEdit(postId) {
        const editForm = document.getElementById(`edit-form-${postId}`);
        editForm.style.display = 'none';
    }

    function updatePost(postId, form) {
        const formData = new FormData(form);

        fetch('update_post.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Atualiza o conteúdo do post na página
                const postTitle = form.querySelector('input[name="titulo"]').value;
                const postBody = form.querySelector('textarea[name="corpo"]').value;
                document.querySelector(`article h2`).innerText = postTitle;
                document.querySelector(`article p`).innerText = postBody;

                alert('Post atualizado com sucesso!');

                // Atualiza a página após 0,3 segundos
                setTimeout(() => {
                    location.reload();
                }, 1);
            } else {
                alert('Erro ao atualizar o post: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
        });

        return false; // Impede o envio padrão do formulário
    }
</script>

<?php include_once('../includes/footer.php'); ?>
