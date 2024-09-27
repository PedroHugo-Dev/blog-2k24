<?php
session_start();

// Verifica se o usuário está autenticado (verifica se a sessão está ativa e se o usuário está logado)
    // Redireciona para a página home
    
    $acao = filter_var(isset($_GET['acao']) ? $_GET['acao'] : 'negado', FILTER_SANITIZE_STRING);
    if ($acao !== "true" && $acao !== "negado"){
    
    header("Location: paginas/home.php");
    exit(); // Certifique-se de usar exit() após header()
    }
?>
<?php
            include_once('config/conexao.php');
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
                                <strong>Logado com sucesso!</strong> Você será redirecionado para o StarBlog :)</div>';

                                header("Location: paginas/home.php?acao=bemvindo");
                                exit(); // Certifique-se de usar exit() após header()
                            } else {
                                echo '<div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>Erro!</strong> Senha incorreta, tente novamente.</div>';
                               // header("Location: index.php?acao=negado");
                               // exit(); // Certifique-se de usar exit() após header()
                            }
                        } else {
                            echo '<div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>Erro!</strong> E-mail não encontrado, verifique seu login ou faça o cadastro.</div>';
                            //header("Location: index.php?acao=negado");
                            //exit(); // Certifique-se de usar exit() após header()
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
<!DOCTYPE html>
<html lang="pt_br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>StarBlog | Log in</title>
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
    <!-- CSS Personalizado -->
    <style>
        /* Reseta estilos padrão */
        body, h1, form, input, button {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Define estilos gerais do corpo */
        body {
            font-family: 'Source Sans Pro', sans-serif;
            background-color: #f0f4f8; /* Cor de fundo leve */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Estiliza o contêiner de login */
        .login-box {
            background-color: #ffffff; /* Fundo branco para o formulário */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        /* Estiliza o logo */
        .login-logo a {
            color: #0056b3; /* Azul escuro para o título */
            font-size: 24px;
            text-decoration: none;
            font-weight: bold;
        }

        /* Estiliza o título do login */
        .login-box-msg {
            color: #0056b3; /* Azul escuro para a mensagem */
            margin-bottom: 20px;
        }

        /* Estiliza os rótulos dos campos */
        .input-group .form-control {
            border-color: #0056b3; /* Azul para a borda dos campos */
            border-radius: 4px;
            padding: 10px;
        }

        /* Estiliza o botão de login */
        .btn-primary {
            background-color: #ffc107; /* Amarelo para o fundo do botão */
            border-color: #ffc107; /* Amarelo para a borda do botão */
            color: #0056b3; /* Azul para o texto do botão */
            font-weight: bold;
        }

        .btn-primary:hover {
            background-color: #e0a800; /* Tom mais escuro de amarelo quando o botão é hover */
            border-color: #e0a800; /* Tom mais escuro de amarelo para a borda */
        }

        /* Estiliza as mensagens de alerta */
        .alert {
            margin-top: 10px;
            border-radius: 4px;
        }

        .alert-success {
            background-color: #d4edda; /* Verde claro para sucesso */
            color: #155724; /* Verde escuro para o texto */
        }

        .alert-danger {
            background-color: #f8d7da; /* Vermelho claro para erro */
            color: #721c24; /* Vermelho escuro para o texto */
        }

        .alert-warning {
            background-color: #fff3cd; /* Amarelo claro para aviso */
            color: #856404; /* Amarelo escuro para o texto */
        }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="../../index2.html"><b>StarBlog</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Para acessar entre com E-mail e Senha</p>

            <form action="" method="post">
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Digite seu E-mail..." required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="senha" class="form-control" placeholder="Digite sua Senha..." required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <!-- Espaço reservado para possíveis links de recuperação ou lembrete -->
                    </div>
                    <!-- /.col -->
                    <div class="col-12" style="margin-bottom: 5px">
                        <button type="submit" name="login" class="btn btn-primary btn-block">Acessar StarBlog</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
          <?php
            // Exibir mensagens com base na ação
            if (isset($_GET['acao'])) {
                $acao = $_GET['acao'];
                if ($acao == 'negado') {
                    echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>Erro ao Acessar o sistema!</strong> Efetue o login ;(</div>';
                } elseif ($acao == 'sair') {
                    echo '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>Você acabou de sair de StarBlog!</strong> :(</div>';
                }
            }
            ?>

            <p style="text-align: center; padding-top: 25px">
                <a href="cad_user.php?redirecionamento=true" class="text-center">Se ainda não tem cadastro clique aqui!</a>
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
