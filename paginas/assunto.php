<?php
include_once('../includes/header.php');
include_once("../config/conexao.php");

// Sanitização de entrada
$acao = filter_var(isset($_GET['assunto']) ? $_GET['assunto'] : 'Teste', FILTER_SANITIZE_STRING);

// Obtendo os tópicos permitidos do banco de dados
$topicoQuery = $conect->prepare("SELECT DISTINCT nome FROM topico");
$topicoQuery->execute();
$topicos = $topicoQuery->fetchAll(PDO::FETCH_COLUMN);

// Verifica se o tópico solicitado está na lista de tópicos
if (!in_array($acao, $topicos)){
    $acao = "Teste";
}

// Obtendo o id_topico com base no nome do tópico
$topicoQuery = $conect->prepare("SELECT id_topico FROM topico WHERE nome = :nome");
$topicoQuery->execute(['nome' => $acao]);
$topicoResult = $topicoQuery->fetch(PDO::FETCH_ASSOC);

// Verifica se o tópico existe
if ($topicoResult) {
    $id_topico = $topicoResult['id_topico'];

    // Consulta os posts relacionados ao id_topico
    $query = $conect->prepare("SELECT * FROM post WHERE id_topico = :id_topico ORDER BY data_criacao DESC");
    $query->execute(['id_topico' => $id_topico]);
    $posts = $query->fetchAll(PDO::FETCH_ASSOC);
} else {
    $posts = [];
}
?>

<style>
/* Estilizando o card e posts */
.card-body {
   padding: 20px;
}

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

.comentarios {
   background-color: #f8f9fa; /* Fundo claro para os comentários */
   border-left: 4px solid #ffc107; /* Borda amarela */
   padding: 10px 15px; /* Padding interno */
   margin-top: 15px; /* Espaçamento acima */
   border-radius: 5px; /* Bordas arredondadas */
}

.comentario {
   margin-bottom: 10px; /* Espaçamento entre comentários */
}
</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Blog - <?php echo ucfirst(htmlspecialchars($acao)); ?></h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="posts" id="posts">
                                <?php if ($posts): ?>
                                    <?php foreach ($posts as $post): ?>
                                        <article>
                                            <h2><?php echo htmlspecialchars($post['titulo']); ?></h2>
                                            <p><?php echo nl2br(htmlspecialchars($post['corpo'])); ?></p>
                                            <p><small>Postado em: <?php echo $post['data_criacao']; ?></small></p>
                                            
                                            <button class="btn btn-primary" onclick="toggleComentarios(<?php echo $post['id_post']; ?>)">Exibir Comentários</button>
                                            
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
        </div><!-- /.container-fluid -->
    </section>
</div>

<?php include_once('../includes/footer.php'); ?>

<script>
function toggleComentarios(postId) {
    const comentariosDiv = document.getElementById(`comentarios-${postId}`);
    if (comentariosDiv.style.display === 'none') {
        comentariosDiv.style.display = 'block';
    } else {
        comentariosDiv.style.display = 'none';
    }
}
</script>
