<?php
  
  session_start(); 

  // Verifica se o usuário está autenticado (verifica se a sessão está ativa e se o usuário está logado)
  if (isset($_SESSION['loginUser']) && $_SESSION['senhaUser'] === true) {
      // Redireciona para a página home
      header("Location: paginas/home.php");
  }
  
?>

<!DOCTYPE html>
<html lang="pt_br">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>New Agenda 2.0 | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>New Agenda</b> 2.0</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Para acessar entre com E-mail e Senha</p>

      <form action="" method="post">
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Digite seu E-mail...">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="senha" class="form-control" placeholder="Digite sua Senha...">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            
          </div>
          <!-- /.col -->
          <div class="col-12" style="margin-bottom: 5px">
            <button type="submit" name="login" class="btn btn-primary btn-block">Acessar a Agenda</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <?php
       
include_once('config/conexao.php');
                   
// Exibir mensagens com base na ação
if (isset($_GET['acao'])) {
    $acao = $_GET['acao'];
    if ($acao == 'negado') {
        echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button>
        <strong>Erro ao Acessar o sistema!</strong> Efetue o login ;(</div>';
       
    } elseif ($acao == 'sair') {
        echo '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">×</button>
        <strong>Você acabou de sair da Agenda Eletrônica!</strong> :(</div>';
       
    }
}

// Processar o formulário de login
if (isset($_POST['login'])) {
    $login = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $senha = filter_input(INPUT_POST, 'senha', FILTER_DEFAULT);

    if ($login && $senha) {
        $select = "SELECT * FROM tb_user WHERE email_user = :emailLogin";

        try {
            $resultLogin = $conect->prepare($select);
            $resultLogin->bindParam(':emailLogin', $login, PDO::PARAM_STR);
            $resultLogin->execute();

            $verificar = $resultLogin->rowCount();
            if ($verificar > 0) {
                $user = $resultLogin->fetch(PDO::FETCH_ASSOC);

                // Verifica a senha
                if (password_verify($senha, $user['senha_user'])) {
                    // Criar sessão
                    $_SESSION['loginUser'] = $login;
                    $_SESSION['senhaUser'] = $user['id_user'];

                    echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>Logado com sucesso!</strong> Você será redirecionado para a agenda :)</div>';

                    header("Refresh: 5; url=paginas/home.php?acao=bemvindo");
                } else {
                    echo '<div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>Erro!</strong> Senha incorreta, tente novamente.</div>';
                    header("Refresh: 7; url=index.php");
                }
            } else {
                echo '<div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Erro!</strong> E-mail não encontrado, verifique seu login ou faça o cadastro.</div>';
                header("Refresh: 7; url=index.php");
            }
        } catch (PDOException $e) {
            // Log the error instead of displaying it to the user
            error_log("ERRO DE LOGIN DO PDO: " . $e->getMessage());
            echo '<div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>Erro!</strong> Ocorreu um erro ao tentar fazer login. Por favor, tente novamente mais tarde.</div>';
        }
    } else {
        echo '<div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>Erro!</strong> Todos os campos são obrigatórios.</div>';
    }
}
      ?>
      
     
      <!-- /.social-auth-links -->

      <p style="text-align: center; padding-top: 25px">
        <a href="cad_user.php" class="text-center">Se ainda não tem cadastro clique aqui!</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

</body>
</html>