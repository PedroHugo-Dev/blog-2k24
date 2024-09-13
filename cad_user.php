<!DOCTYPE html>
<html lang="pt_br">
<head>
 <meta charset="utf-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <title>StarBlog | Cadastro de Usuário</title>
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


   /* Estiliza o botão de cadastro */
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
   <a href="cad_user.php" style="font-size: 25px"><b>Cadastre-se</b></a>
 </div>
 <!-- /.login-logo -->
 <div class="card">
   <div class="card-body login-card-body">
     <p class="login-box-msg">Cadastre todos os dados para ter acesso ao StarBlog</p>


     <form action="" method="post" enctype="multipart/form-data">
       <div class="form-group">
         <label for="foto">Foto do usuário</label>
         <div class="input-group">
           <div class="custom-file">
             <input type="file" class="custom-file-input" name="foto" id="foto">
             <label class="custom-file-label" for="foto">Arquivo de imagem</label>
           </div>
         </div>
       </div>
       <div class="input-group mb-3">
         <input type="text" name="nome" class="form-control" placeholder="Digite seu Nome..." required>
         <div class="input-group-append">
           <div class="input-group-text">
             <span class="fas fa-user"></span>
           </div>
         </div>
       </div>
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
           <!-- Espaço vazio, pode ser usado para algo mais no futuro -->
         </div>
         <!-- /.col -->
         <div class="col-12" style="margin-bottom: 25px">
           <button type="submit" name="botao" class="btn btn-primary btn-block">Finalizar Cadastro</button>
     </p>
         </div>
         <div class="col-12">
           <p style="text-align: center;">
       <a href="index.php" class="text-center">Voltar para o Login!</a>
     </p>
         </div>
         
         <!-- /.col -->
      </div>
     </form>
   </div>
 </div>
</div>
</body>
</html>


    
<?php
include('config/conexao.php'); // Inclui o arquivo de conexão com o banco de dados


// Verifica se o formulário foi enviado
if (isset($_POST['botao'])) {
// Recebe os dados do formulário
$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Usando hash seguro para a senha


// Verifica se foi enviado algum arquivo de foto
if (!empty($_FILES['foto']['name'])) {
 $formatosPermitidos = array("png", "jpg", "jpeg", "gif"); // Formatos permitidos
 $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION); // Obtém a extensão do arquivo


 // Verifica se a extensão do arquivo está nos formatos permitidos
 if (in_array(strtolower($extensao), $formatosPermitidos)) {
     $pasta = "img/"; // Define o diretório para upload
     $temporario = $_FILES['foto']['tmp_name']; // Caminho temporário do arquivo
     $novoNome = uniqid() . ".$extensao"; // Gera um nome único para o arquivo


     // Move o arquivo para o diretório de imagens
     if (move_uploaded_file($temporario, $pasta . $novoNome)) {
         // Sucesso no upload da imagem
     } else {
         echo '<div class="container">
                 <div class="alert alert-danger alert-dismissible">
                     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                     <h5><i class="icon fas fa-exclamation-triangle"></i> Erro!</h5>
                     Não foi possível fazer o upload do arquivo.
                 </div>
             </div>';
         exit(); // Termina a execução do script após o erro
       }
 } else {
     echo '<div class="container">
             <div class="alert alert-danger alert-dismissible">
                 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                 <h5><i class="icon fas fa-exclamation-triangle"></i> Formato Inválido!</h5>
                 Formato de arquivo não permitido.
             </div>
         </div>';
     exit(); // Termina a execução do script após o erro
 }
} else {
 // Define um avatar padrão caso não seja enviado nenhum arquivo de foto
 $novoNome = 'avatar-padrao.png'; // Nome do arquivo de avatar padrão
}


// Prepara a consulta SQL para inserção dos dados do usuário
$cadastro = "INSERT INTO tb_user (foto_user, nome_user, email_user, senha_user, administrador) VALUES (:foto, :nome, :email, :senha, 0)";


try {
 $result = $conect->prepare($cadastro);
 $result->bindParam(':nome', $nome, PDO::PARAM_STR);
 $result->bindParam(':email', $email, PDO::PARAM_STR);
 $result->bindParam(':senha', $senha, PDO::PARAM_STR);
 $result->bindParam(':foto', $novoNome, PDO::PARAM_STR);
 $result->execute();
  $contar = $result->rowCount();


 if ($contar > 0) {
     echo '<div class="container">
             <div class="alert alert-success alert-dismissible">
                 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                 <h5><i class="icon fas fa-check"></i> OK!</h5>
                 Dados inseridos com sucesso !!!
             </div>
         </div>';
 } else {
     echo '<div class="container">
             <div class="alert alert-danger alert-dismissible">
                 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                 <h5><i class="icon fas fa-check"></i> Erro!</h5>
                 Dados não inseridos !!!
             </div>
         </div>';
 }
} catch (PDOException $e) {
 // Loga a mensagem de erro em vez de exibi-la para o usuário
 error_log("ERRO DE PDO: " . $e->getMessage());
 echo '<div class="container">
         <div class="alert alert-danger alert-dismissible">
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             <h5><i class="icon fas fa-exclamation-triangle"></i> Erro!</h5>
             Ocorreu um erro ao tentar inserir os dados.
         </div>
     </div>';
}
}
?>
    
   
     <!-- /.social-auth-links -->


    
     
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

