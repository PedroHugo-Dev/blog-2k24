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
  <title>Agenda Eletrônica</title>
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

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      
    </ul>

    

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" title="Perfil e Saída">
          <i class="fas fa-user-circle"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          
          <div class="dropdown-divider"></div>
          <a href="home.php?acao=perfil" class="dropdown-item">
          <i class="fas fa-user-alt mr-2"></i></i> Alterar Perfil
            
          </a>
          <div class="dropdown-divider"></div>
          <a href="?sair" class="dropdown-item">
          <i class="fas fa-sign-out-alt mr-2"></i> Sair da Agenda
            
          </a>
          
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <span class="brand-text font-weight-light">Agenda Eletrônica</span>
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
                echo '<img src="../img/avatar_p/' . $foto_user . '" alt="' . $foto_user . '" title="' . $nome_user . '" style="width: 40px; border-radius: 100%;">';
            } else {
                // Exibe a imagem do usuário
                echo '<img src="../img/user/' . $foto_user . '" alt="' . $foto_user . '" title="' . $nome_user . '" style="width: 40px; border-radius: 100%;">';
            }
          ?>
        </div>
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
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Principal
                
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="home.php?acao=relatorio" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Relatório
                
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
