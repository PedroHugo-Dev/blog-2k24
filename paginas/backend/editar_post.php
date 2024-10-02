<?php
include_once('../../config/conexao.php');

// Verifica se o usuário está autenticado, se necessário
session_start();
if (!isset($_SESSION['loginUser'])) {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_post = filter_input(INPUT_POST, 'id_post', FILTER_SANITIZE_NUMBER_INT);

    // Buscar o post atual
    $query = "SELECT * FROM post WHERE id_post = :id_post";
    $stmt = $conect->prepare($query);
    $stmt->bindParam(':id_post', $id_post, PDO::PARAM_INT);
    $stmt->execute();
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        die("Post não encontrado.");
    }

    // Se o formulário de edição foi enviado
    if (isset($_POST['titulo']) && isset($_POST['corpo'])) {
        $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_STRING);
        $corpo = filter_input(INPUT_POST, 'corpo', FILTER_SANITIZE_STRING);

        $updateQuery = "UPDATE post SET titulo = :titulo, corpo = :corpo WHERE id_post = :id_post";
        $updateStmt = $conect->prepare($updateQuery);
        $updateStmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
        $updateStmt->bindParam(':corpo', $corpo, PDO::PARAM_STR);
        $updateStmt->bindParam(':id_post', $id_post, PDO::PARAM_INT);
        
        if ($updateStmt->execute()) {
            header('Location: ../home.php');
            exit();
        } else {
            echo "Erro ao atualizar post.";
        }
    }
} else {
    die("Método não suportado.");
}
?>

<!DOCTYPE html>
<html lang="pt_br">
<head>
 <meta charset="utf-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <title>StarBlog | Editar Post</title>
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
   body {
       font-family: 'Source Sans Pro', sans-serif;
       background-color: #f0f4f8;
       display: flex;
       justify-content: center;
       align-items: center;
       height: 100vh;
   }
   .login-box {
       background-color: #ffffff;
       padding: 20px;
       border-radius: 8px;
       box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
       width: 100%;
       max-width: 600px;
   }
   .btn-primary {
       background-color: #ffc107;
       border-color: #ffc107;
       color: #0056b3;
       font-weight: bold;
   }
   .btn-primary:hover {
       background-color: #e0a800;
       border-color: #e0a800;
   }
 </style>
</head>

<body class="hold-transition login-page">
<div class="login-box">
 <div class="login-logo">
   <a href="editar_post.php?id_post=<?php echo $post['id_post']; ?>" style="font-size: 25px"><b>Editar Post</b></a>
 </div>
 <div class="card">
   <div class="card-body login-card-body">
     <p class="login-box-msg">Edite os dados do post abaixo:</p>

     <form method="post" action="editar_post.php">
       <input type="hidden" name="id_post" value="<?php echo $post['id_post']; ?>">
       <div class="input-group mb-3">
         <input type="text" name="titulo" class="form-control" placeholder="Digite o Título..." value="<?php echo htmlspecialchars($post['titulo']); ?>" required>
         <div class="input-group-append">
           <div class="input-group-text">
             <span class="fas fa-heading"></span>
           </div>
         </div>
       </div>
       <div class="form-group">
         <label for="corpo">Corpo do Post</label>
         <textarea name="corpo" class="form-control" rows="5" required><?php echo htmlspecialchars($post['corpo']); ?></textarea>
       </div>
       <div class="row">
         <div class="col-12">
           <button type="submit" name="botao" class="btn btn-primary btn-block">Salvar</button>
         </div>
         <div class="col-12">
           <p style="text-align: center;">
             <a href="../home.php" class="text-center">Voltar para o Home!</a>
           </p>
         </div>
       </div>
     </form>
   </div>
 </div>
</div>
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
