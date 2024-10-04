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
 <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
 <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
 <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
 <link rel="stylesheet" href="dist/css/adminlte.min.css">
 <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
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

   /* Estiliza o contêiner de edição do post */
   .edit-post-box {
       background-color: #ffffff; /* Fundo branco para o formulário */
       padding: 30px;
       border-radius: 8px;
       box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
       width: 100%;
       max-width: 600px;
       text-align: left; /* Alinha o texto à esquerda */
   }

   /* Estiliza os rótulos dos campos */
   .form-group label {
       font-weight: bold;
       color: #0056b3; /* Azul escuro para o rótulo */
       margin-bottom: 5px; /* Espaçamento abaixo do rótulo */
   }

   /* Estiliza os campos de entrada */
   .input-group .form-control {
       border-color: #0056b3; /* Azul para a borda dos campos */
       border-radius: 8px; /* Bordas arredondadas */
       padding: 10px;
       height: 45px; /* Altura dos inputs */
   }

   /* Estiliza o título do post */
   .titulo-post {
       font-size: 1.5rem; /* Aumenta o tamanho do texto do título */
       margin-top: 5px; /* Espaçamento acima do título */
       margin-bottom: 15px; /* Espaçamento abaixo do título */
       color: #333; /* Cor do título */
   }

   /* Estiliza o textarea */
   textarea {
       border-color: #0056b3; /* Azul para a borda do textarea */
       border-radius: 8px; /* Bordas arredondadas */
       padding: 10px;
       width: calc(100% - 22px); /* Largura total com padding */
       height: 150px; /* Altura fixa para o textarea */
       resize: none; /* Impede redimensionamento manual */
   }

   /* Estiliza o botão de salvar */
   .btn-primary {
       background-color: #ffc107; /* Amarelo para o fundo do botão */
       border-color: #ffc107; /* Amarelo para a borda do botão */
       color: #0056b3; /* Azul para o texto do botão */
       font-weight: bold;
       border-radius: 5px; /* Bordas arredondadas no botão */
       padding: 10px 20px; /* Espaçamento interno do botão */
       width: 100%; /* Largura total do botão */
   }

   .btn-primary:hover {
       background-color: #e0a800; /* Tom mais escuro de amarelo quando o botão é hover */
       border-color: #e0a800; /* Tom mais escuro de amarelo para a borda */
   }

   .text-center {
       text-align: center;
       color: #0056b3; /* Azul escuro para o texto */
   }
 </style>
</head>

<body>
<div class="edit-post-box">
 <div class="text-center mb-4">
   <h2><b>Editar Post</b></h2>
   <p>Altere as informações do post abaixo:</p>
 </div>

 <form method="post" action="editar_post.php">
   <input type="hidden" name="id_post" value="<?php echo $post['id_post']; ?>">
   <div class="form-group mb-3 row">
     <label for="titulo" class="col-sm-2 col-form-label">Título do Post</label>
     <div class="col-sm-10">
       <textarea name="titulo" class="form-control" rows="1" required><?php echo htmlspecialchars($post['titulo']); ?></textarea>
     </div>
   </div>
   <div class="form-group row">
     <label for="corpo" class="col-sm-2 col-form-label">Corpo do Post</label>
     <div class="col-sm-10">
       <textarea name="corpo" class="form-control" required><?php echo htmlspecialchars($post['corpo']); ?></textarea>
     </div>
   </div>
   <div class="row">
     <div class="col-12">
       <button type="submit" name="botao" class="btn btn-primary">Salvar</button>
     </div>
     <div class="col-12 text-center mt-3">
       <p>
         <a href="../home.php" class="text-muted">Voltar para o Home!</a>
       </p>
     </div>
   </div>
</form>

</div>
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
