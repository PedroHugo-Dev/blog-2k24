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

<div class="content-wrapper">
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary">

            <?php if ($postagens): ?>
                <?php foreach ($postagens as $post): ?>

                    <div class="card-header">
                        <h3 class="card-title">Blog - <?php echo htmlspecialchars($post['topico_nome']); ?></h3>
                    </div>

                    <div class="card-body">
                        <div class="posts">
                            <article>
                                <h2><?php echo htmlspecialchars($post['titulo']); ?></h2>
                                <p><?php echo nl2br(htmlspecialchars($post['corpo'])); ?></p>
                                <p><small>Postado em: <?php echo htmlspecialchars($post['data_criacao']); ?></small></p>
                            </article>
                        <?php endforeach; ?>

                    <?php else: ?>
                        <p>No posts found for this topic.</p>
                    <?php endif; ?>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <?php include_once('../includes/footer.php'); ?>
