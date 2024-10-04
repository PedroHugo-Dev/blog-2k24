<?php
include_once('../../config/conexao.php');

// Verifica se o usuário está autenticado
session_start();
if (!isset($_SESSION['loginUser'])) {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_comentario = filter_input(INPUT_POST, 'id_comentario', FILTER_SANITIZE_NUMBER_INT);

    // Buscar o comentário atual
    $query = "SELECT * FROM comentario WHERE id_comentario = :id_comentario";
    $stmt = $conect->prepare($query);
    $stmt->bindParam(':id_comentario', $id_comentario, PDO::PARAM_INT);
    $stmt->execute();
    $comentario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$comentario) {
        die("Comentário não encontrado.");
    }

    // Se o formulário de edição foi enviado
    if (isset($_POST['corpo'])) {
        $corpo = filter_input(INPUT_POST, 'corpo', FILTER_SANITIZE_STRING);

        $updateQuery = "UPDATE comentario SET corpo = :corpo WHERE id_comentario = :id_comentario";
        $updateStmt = $conect->prepare($updateQuery);
        $updateStmt->bindParam(':corpo', $corpo, PDO::PARAM_STR);
        $updateStmt->bindParam(':id_comentario', $id_comentario, PDO::PARAM_INT);
        
        if ($updateStmt->execute()) {
            header('Location: ../home.php');
            exit();
        } else {
            echo "Erro ao atualizar comentário.";
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
    <title>StarBlog | Editar Comentário</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
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

        /* Estiliza o contêiner de edição do comentário */
        .edit-comment-box {
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
<div class="edit-comment-box">
    <div class="text-center mb-4">
        <h2><b>Editar Comentário</b></h2>
        <p>Altere o comentário abaixo:</p>
    </div>

    <form method="post" action="editar_comentario.php">
        <input type="hidden" name="id_comentario" value="<?php echo $comentario['id_comentario']; ?>">
        <div class="form-group mb-3">
            <label for="corpo">Comentário</label>
            <textarea name="corpo" class="form-control" required><?php echo htmlspecialchars($comentario['corpo']); ?></textarea>
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
