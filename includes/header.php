<?php
// Inicia o buffer de saída
ob_start();

// Inicia a sessão apenas se ainda não tiver sido iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica se as variáveis de sessão estão definidas
if (!isset($_SESSION['loginUser'])) {
    // Redireciona para a página inicial com a mensagem de acesso negado
    header("Location: ../index.php?acao=negado");
    exit;
}

// Inclui o script de saída
include_once('sair.php'); 
?>
<!DOCTYPE html>
<html lang="pt_br">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Blog</title>
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.dataTables.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.1/css/buttons.dataTables.css">

  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="../plugins/summernote/summernote-bs4.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link rel="stylesheet" href="../dist/css/estilo.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">

<div class="wrapper">

<?php
// Inclui o arquivo de configuração de conexão com o banco de dados
include_once('../config/conexao.php');

// Obtém o email do usuário logado a partir da sessão
$usuarioLogado = $_SESSION['loginUser'];

// Define a consulta SQL para selecionar todos os campos do usuário com base no email
$selectUser = "SELECT * FROM tb_user WHERE email_user=:emailUserLogado";

try {
    // Prepara a consulta SQL
    $resultadoUser = $conect->prepare($selectUser);
    
    // Vincula o parâmetro :emailUserLogado ao valor da variável $usuarioLogado
    $resultadoUser->bindParam(':emailUserLogado', $usuarioLogado, PDO::PARAM_STR);
    
    // Executa a consulta preparada
    $resultadoUser->execute();

    // Conta o número de linhas retornadas pela consulta
    $contar = $resultadoUser->rowCount();
    
    // Se houver uma ou mais linhas retornadas
    if ($contar > 0) {
        // Obtém a próxima linha do conjunto de resultados como um objeto
        $show = $resultadoUser->fetch(PDO::FETCH_OBJ);
        
        // Atribui os valores dos campos do usuário às variáveis PHP
        $id_user = $show->id_user;
        $foto_user = $show->foto_user;
        $nome_user = $show->nome_user;
        $email_user = $show->email_user;
    } else {
        // Exibe uma mensagem de aviso se não houver dados de perfil
        echo '<div class="alert alert-danger"><strong>Aviso!</strong> Não há dados de perfil :(</div>';
    }
} catch (PDOException $e) {
    // Registra a mensagem de erro no log do servidor em vez de exibi-la ao usuário
    error_log("ERRO DE LOGIN DO PDO: " . $e->getMessage());
    
    // Exibe uma mensagem de erro genérica para o usuário
    echo '<div class="alert alert-danger"><strong>Aviso!</strong> Ocorreu um erro ao tentar acessar os dados do perfil.</div>';
}
?>  

<!-- index.html -->

<nav class="main-header navbar navbar-expand navbar-dark">
  <ul class="navbar-nav">
    <!-- Botão Criar Post -->
    <li class="nav-item">
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#criarPostModal">
        <i class="fas fa-comment-alt mr-2"></i> Criar Post
      </button>
    </li>
    <!-- Botão Criar Tópico -->
    <li class="nav-item">
      <button type="button" class="btn btn-secondary ml-2" data-toggle="modal" data-target="#criarTopicoModal">
        <i class="fas fa-plus mr-2"></i> Criar Tópico
      </button>
    </li>
  </ul>
  <form class="form-inline mx-auto w-50">
    <input type="search" class="form-control w-75" placeholder="Pesquisar...">
    <button type="submit" class="btn btn-primary">
      <i class="fas fa-search mr-2"></i> Pesquisar
    </button>
  </form>
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <a href="?sair">
        <button type="button" class="btn btn-primary">
          <i class="fas fa-sign-out-alt mr-2"></i> Sair
        </button>
      </a>
    </li>
  </ul>
</nav>

<!-- Modal HTML para Criar Post -->
<div class="modal fade" id="criarPostModal" tabindex="-1" role="dialog" aria-labelledby="criarPostModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="criarPostModalLabel">Criar Post</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="criar-post.php">
          <div class="form-group">
            <label for="titulo">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Digite o título do Post" required>
          </div>
          <div class="form-group">
            <label for="descricao">Descrição</label>
            <textarea class="form-control" id="descricao" name="descricao" rows="3" placeholder="Digite a descrição do Post" required></textarea>
          </div>
          <div class="form-group">
            <label for="assunto">Assunto</label>
            <select class="form-control" id="assunto" name="assunto" required>
              <option value="" disabled selected>Selecione o assunto</option>
              <option value="jogos">Jogos</option>
              <option value="filmes">Filmes</option>
              <option value="tecnologias">Tecnologias</option>
            </select>
          </div>
          <input type="hidden" name="id_topico" value="1"> <!-- Ajuste conforme necessário -->
          <input type="hidden" name="id_user" value="1"> <!-- Ajuste conforme necessário -->
          <button type="submit" class="btn btn-primary">Criar Post</button>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- Modal HTML para Criar Tópico -->
<div class="modal fade" id="criarTopicoModal" tabindex="-1" role="dialog" aria-labelledby="criarTopicoModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="criarTopicoModalLabel">Criar Tópico</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="criar-topico.php">
          <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome do Tópico" required>
          </div>
          <div class="form-group">
            <label for="descricao">Descrição</label>
            <textarea class="form-control" id="descricao" name="descricao" rows="3" placeholder="Digite a descrição do Tópico" required></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Criar Tópico</button>
        </form>
      </div>
    </div>
  </div>
</div>




  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <span class="brand-text font-weight-light">Blog||JMF</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <?php
              // Verifica se a variável $foto_user é igual a 'avatar-padrao.png'
              if ($foto_user == 'avatar-padrao.png') {
                  // Exibe a imagem do avatar padrão
                  echo '<img src="../img/avatar_p/' . $foto_user . '" alt="' . $foto_user . '" title="' . $nome_user . '" style="width: 40px; border-radius: 100%;" class="foto-perfil">';
              } else {
                  // Exibe a imagem do usuário
                  echo '<img src="../img/user/' . $foto_user . '" alt="' . $foto_user . '" title="' . $nome_user . '" style="width: 40px; border-radius: 100%;" class="foto-perfil">';
              }
            ?>
          </div>

          <script>
            const imgPerfil = document.querySelector('.foto-perfil');
            imgPerfil.addEventListener('click', function() {
              window.location.href = 'home.php?acao=perfil';
            });
          </script>
          <div class="info">
            <a href="#" class="d-block"><?php echo $nome_user; ?></a>
          </div>
        </div>
      <!-- Sidebar Menu -->
      <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="home.php?acao=bemvindo" class="nav-link">
              <p>
                Home
              </p>
            </a>
          </li>
        </ul>
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->



          <li class="nav-item menu-open">
            <a href="#" class="nav-link">
              <p>
              Categorias
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">

            <li class="nav-item">
                <a href="./assunto.php" class="nav-link">
                  <p>Jogos</p>
                </a>
              </li>

              <li class="nav-im">
                <a href="./assunto.php?assunto=filmes" class="nav-link">
                  <p>Filmes</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="./assunto.php?assunto=tecnologias" class="nav-link">
                  <p>Tecnologias</p>
                </a>
              </li>

              <?php 
                $query = $conect->prepare("SELECT * FROM topico ORDER BY nome DESC");
                $query->execute();
                $topicos = $query->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <?php foreach ($topicos as $topico): ?>
                  <li class="nav-item">
                    <a href="./assunto.php?id_topico=<?= $topico['nome'] ?>" class="nav-link">
                      <p><?= htmlspecialchars($topico['nome']); ?></p>
                    </a>
                  </li>
              <?php endforeach; ?>
            </ul>
          </li>
          <li class="nav-item menu-open">
            <a href="#" class="nav-link">
              <p>
              Recursos
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index1.php" class="nav-link">
                  <p>Sobre o site</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index2.php" class="nav-link">
                  <p>Ajuda</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index3.php" class="nav-link">
                  <p>Contatos</p>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>