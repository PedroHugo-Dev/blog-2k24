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
                    <h1>Blog - <?php echo ucfirst(htmlspecialchars($acao)); ?></h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="posts" id="posts">
                                <!-- Posts serão carregados aqui -->

                            
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    var page = 1;
    var loading = false;
    var assunto = "<?php echo htmlspecialchars($acao); ?>";

    function loadPosts() {
        if (loading) return;
        loading = true;
        $('#loading').show();

        $.ajax({
            url: 'load_posts.php',
            type: 'GET',
            data: { assunto: assunto, page: page },
            success: function(data) {
                var posts = JSON.parse(data);
                if (posts.length > 0) {
                    $.each(posts, function(index, post) {
                        var html = '<article>';
                        html += '<h2>' + $('<div/>').text(post.titulo).html() + '</h2>';
                        html += '<p>' + $('<div/>').text(post.corpo).html().replace(/\n/g, '<br>') + '</p>';
                        html += '<p><small>Postado em: ' + post.data_criacao + '</small></p>';
                        
                        // Renderizando comentários
                        html += '<div class="comments" id="comments-' + post.id_post + '">';
                        html += loadComments(post.id_post); // Carregar comentários diretamente
                        html += '</div>';
                        
                        // Formulário para adicionar comentário
                        html += renderCommentForm(post.id_post, post.id_topico);
                        html += '<form method="post" action="backend/remover_post.php" style="display:inline;">';
                        html += '<input type="hidden" name="id_post" value="' + post.id_post + '">';
                        html += '<button type="submit" class="btn btn-danger" onclick="return confirm(\'Tem certeza que deseja remover este post?\');">Remover</button>';
                        html += '</form>';
                        html += '</article><hr style="border: 1px solid #ffc107;">';
                        $('#posts').append(html);
                    });
                    page++;
                } else {
                    $(window).off('scroll', onScroll);
                }
                loading = false;
                $('#loading').hide();
            }
        });
    }

    function loadComments(postId) {
        var commentsHtml = ''; // HTML para os comentários
        $.ajax({
            url: 'load_comments.php',
            type: 'GET',
            data: { id_post: postId },
            async: false, // Faz com que a chamada seja síncrona
            success: function(data) {
                var comments = JSON.parse(data);
                if (comments.length > 0) {
                    $.each(comments, function(index, comment) {
                        commentsHtml += '<div class="comentario">';
                        commentsHtml += '<p><strong>' + $('<div/>').text(comment.nome_user).html() + ':</strong></p>';
                        commentsHtml += '<p>' + $('<div/>').text(comment.corpo).html().replace(/\n/g, '<br>') + '</p>';
                        commentsHtml += '<p><small>Comentário postado em: ' + comment.data_criacao + '</small></p>';
                        commentsHtml += '</div>';
                    });
                } else {
                    commentsHtml += '<p>Nenhum comentário encontrado.</p>';
                }
            }
        });
        return commentsHtml; // Retorna os comentários gerados
    }

    function renderCommentForm(postId, topicoId) {
        return `
            <form method="post" action="adicionar_comentario.php">
                <input type="hidden" name="id_post" value="${postId}">
                <input type="hidden" name="id_topico" value="${topicoId}">
                <div class="form-group">
                    <label for="comentario">Adicionar um comentário:</label>
                    <textarea id="comentario" name="texto_comentario" class="form-control" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Comentar</button>
            </form>
        `;
    }

    function onScroll() {
        if ($(window).scrollTop() + $(window).height() + 100 > $(document).height()) {
            loadPosts();
        }
    }

    $(window).on('scroll', onScroll);
    loadPosts();  // Carregar posts inicialmente
});
</script>
