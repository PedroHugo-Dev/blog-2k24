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
    article {
    position: relative; /* Adiciona um contexto de posicionamento */
}

.button-container {
    position: absolute; /* Posiciona os botões em relação ao artigo */
    top: 10px; /* Ajuste conforme necessário */
    right: 10px; /* Ajuste conforme necessário */
    display: flex;
    justify-content: flex-end;
}


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


    /* Estilo para o título do blog */
    .blog-title {
        font-size: 28px; /* Aumenta o tamanho da fonte */
        font-weight: 700; /* Deixa o texto em negrito */
        color: #343a40; /* Cor do texto (similar ao restante) */
        text-transform: uppercase; /* Transforma o texto em maiúsculas */
        border-bottom: 2px solid #ffc107; /* Borda inferior para destaque */
        padding-bottom: 10px; /* Espaçamento abaixo do título */
    }

    /* Melhorando a responsividade */
    @media (max-width: 768px) {
        .blog-title {
            font-size: 24px; /* Reduz o tamanho em telas menores */
        }
    }

    </style>

    <!-- Content Wrapper. Contains page content -->
    <?php if ($acao !== 'perfil') { ?>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="blog-title">Blog - Home</h1>
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
    <?php } ?>

    <?php include_once('../includes/footer.php'); ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
$(document).ready(function() {
    <?php if ($acao !== 'perfil') { ?>

    
    console.log("SIM")
    var page = 1;
    var loading = false;
    var assunto = "<?php echo htmlspecialchars($acao); ?>";
    var usuarioLogado = -1;
    var adm = 0;
    <?php
        if ($_SESSION['loginUser'] !== 'Guest'){

    ?>

    var usuarioLogado = "<?php echo htmlspecialchars($id_user); ?>"; // Usuário logado
    var adm = "<?php echo htmlspecialchars($adm); ?>"; // Usuário logado
    <?php
        }
    ?> 

    function toggleComentarios(postId) {

        var comentariosDiv = document.getElementById(`comments-${postId}`)
        if (comentariosDiv.style.display === 'none') {
            comentariosDiv.style.display = 'block';
        } else {
            comentariosDiv.style.display = 'none';
        }

    }

    function loadRandomPosts() {
    if (loading) return;
    loading = true;
    $('#loading').show();

    $.ajax({
        url: 'carregar_posts.php', // URL do script que carrega posts aleatórios
        type: 'GET',
        data: { page: page },
        success: function(data) {
            var posts = JSON.parse(data);
            if (posts.length > 0) {
                $.each(posts, function(index, post) {
                    var html = '<article>';

                    html += '<h2>' + $('<div/>').text(post.titulo).html() + '</h2>';
                    html += '<p><small><i>Postado por: ' + $('<div/>').text(post.usuario_nome).html() + '</small></p></i>';
                    html += '<p>' + $('<div/>').text(post.corpo).html().replace(/\n/g, '<br>') + '</p>';
                    html += '<p><small>Da categoria: ' + $('<div/>').text(post.topico_nome).html() + '</small></p>'; // Adicionando o nome do tópico
                    html += '<p><small>Postado em: ' + post.data_criacao + '</small></p>';

                    // Renderizando comentários
                    html += '<div class="comentarios" id="comments-' + post.id_post + '" style="display:none;">';
                    html += loadComments(post.id_post); // Carregar comentários diretamente
                    html += renderCommentForm(post.id_post, post.id_topico);
                    html += '</div>';

                    // Botões no canto superior direito
                    html += `
                        <div class="button-container" style="display: flex; justify-content: flex-end;">
                            <button class="btn btn-primary" data-post-id="${post.id_post}">
                                <i class="fas fa-comments"></i>
                            </button>
                    `;

                    if (post.id_user == usuarioLogado || adm == 1) {
                        html += `
                            <form method="post" action="backend/editar_post.php" style="display:inline;">
                                <input type="hidden" name="id_post" value="${post.id_post}">
                                <button type="submit" class="btn btn-warning" style="margin-left: 10px;">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                            </form>
                            <form method="post" action="backend/remover_post.php" style="display:inline;">
                                <input type="hidden" name="id_post" value="${post.id_post}">
                                <button type="submit" class="btn btn-danger" style="margin-left: 10px;" onclick="return confirm('Tem certeza que deseja deletar?');">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            
                        `;
                    }

                    html += '</div>'; // Fechando a div dos botões
                    html += '</article><hr style="border: 1px solid #ffc107;">';
                    $('#posts').append(html);

                    if (posts.length < 5) {
                        $(window).off('scroll', onScroll);
                    }
                });
                page++;
            } else {
                if (page === 1) {
                    var html = '<article>';
                    html += '<h1>Nenhum post encontrado </h1>';
                    html += '</article><hr style="border: 1px solid #ffc107;">';
                    $('#posts').append(html);

                    $(window).off('scroll', onScroll);
                }
            }
            loading = false;
            $('#loading').hide();
        }
    });
}

function loadComments(postId) {
    var commentsHtml = ''; // HTML for the comments
    $.ajax({
        url: 'load_comments.php',
        type: 'GET',
        data: { id_post: postId },
        async: false, // Make this call synchronous
        success: function(data) {
            var comments = JSON.parse(data);
            if (comments.length > 0) {
                $.each(comments, function(index, comment) {
                    commentsHtml += '<div class="comentario" id="comment-' + comment.id_comentario + '">';
                    commentsHtml += '<p><strong>' + $('<div/>').text(comment.nome_user).html() + ':</strong></p>';
                    commentsHtml += '<p>' + $('<div/>').text(comment.corpo).html().replace(/\n/g, '<br>') + '</p>';
                    commentsHtml += '<p><small>Comentário postado em: ' + comment.data_criacao + '</small></p>';

                    // Check if the logged-in user can edit the comment
                    if (comment.id_user == usuarioLogado || adm == 1) {
                        commentsHtml += `
                            <form method="post" action="backend/editar_comentario.php" style="display:inline;">
                            <input type="hidden" name="id_comentario" value="${comment.id_comentario}">
                                <button type="submit" class="btn btn-warning" style="margin-left: 10px;">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                            </form>
                            <form method="post" action="backend/remover_comentario.php" style="display:inline;">
                            <input type="hidden" name="id_comentario" value="${comment.id_comentario}">     
                                <button type="submit" class="btn btn-danger" style="margin-left: 10px;" onclick="return confirm('Tem certeza que deseja deletar este comentário?');">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        `;
                    }

                    commentsHtml += '</div>';
                });
            } else {
                commentsHtml += '<p>Nenhum comentário encontrado.</p>';
            }
        }
    });
    return commentsHtml; // Return generated comments
}
    function renderCommentForm(postId, topicoId) {
    // Define the button and textarea variable
    let button, textarea;

    // Check the user's login status
    <?php if ($_SESSION['loginUser'] === 'Guest') { ?>
        button = '<button type="button" class="btn btn-primary" onclick="contaAlerta()">Comentar</button>';
        textarea = '<textarea id="comentario" name="texto_comentario" class="form-control" rows="3" disabled required>' +
                   'Faça login para adicionar um comentário.</textarea>';
    <?php } else { ?>
        button = '<button type="submit" class="btn btn-primary">Comentar</button>';
        textarea = '<textarea id="comentario" name="texto_comentario" class="form-control" rows="3" required></textarea>';
    <?php } ?>

    return `
        <form method="post" action="adicionar_comentario.php">
            <input type="hidden" name="id_post" value="${postId}">
            <input type="hidden" name="id_topico" value="${topicoId}">
            <div class="form-group">
                <label for="comentario">Adicionar um comentário:</label>
                ${textarea}
            </div>
            ${button}
        </form>
    `;
    }

    function onScroll() {
        if ($(window).scrollTop() + $(window).height() + 100 > $(document).height()) {
            loadRandomPosts();
        }
    }

    $(window).on('scroll', onScroll);
    loadRandomPosts();  // Carregar posts aleatórios inicialmente

    $('#posts').on('click', '.btn-primary', function() {
        var postId = $(this).data('post-id');
        toggleComentarios(postId);
    });
});
<?php } ?>
</script>