<?php
include_once('../includes/header.php');


// Sanitização de entrada
$acao = filter_var(isset($_GET['assunto']) ? $_GET['assunto'] : 'Teste', FILTER_SANITIZE_STRING);

include_once("../config/conexao.php");

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
echo $id_topico . "AAaa";

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
            border: 1px 
            solid #00ff00; /* Green color */
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
                            <hr style="border: 1px solid #ffc107;">
                            
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
