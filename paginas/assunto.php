<?php
include_once('../includes/header.php');

// Sanitização de entrada
$acao = filter_var(isset($_GET['assunto']) ? $_GET['assunto'] : 'jogos', FILTER_SANITIZE_STRING);
$topicos = [
    'jogos', 'tecnologias', 'filmes'
];

if (!in_array($acao, $topicos)){
    $acao = "jogos";
}

include_once("../config/conexao.php");

$query = $conect->prepare("SELECT * FROM post WHERE assunto = :assunto ORDER BY data_criacao DESC");
$query->execute(['assunto' => $acao]);
$posts = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Blog - <?php echo ucfirst($acao); ?></h1>
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
              <div class="card-header">
                <h3 class="card-title">Blog - <?php echo ucfirst($acao); ?></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="posts">
                    <?php if ($posts): ?>
                        <?php foreach ($posts as $post): ?>
                            <article>
                                <h2><?php echo htmlspecialchars($post['titulo']); ?></h2>
                                <p><?php echo nl2br(htmlspecialchars($post['corpo'])); ?></p>
                                <p><small>Postado em: <?php echo $post['data_criacao']; ?></small></p>
                            </article>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No posts found for this topic.</p>
                    <?php endif; ?>
                </div>
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
  <!-- /.content-wrapper -->

<?php
include_once('../includes/footer.php');
?>