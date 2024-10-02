<?php
include_once('../../config/conexao.php');
// Exemplo de botão para abrir o modal de edição em uma lista de comentários
foreach ($comentarios as $comentario) {
    echo '<div class="comentario">';
    echo '<p>' . htmlspecialchars($comentario['texto']) . '</p>';
    echo '<button type="button" class="btn btn-warning" onclick="editarComentario(' . $comentario['id_comentario'] . ', \'' . htmlspecialchars($comentario['texto']) . '\')">Editar</button>';
    echo '</div>';
}

// Verifica se o usuário está autenticado, se necessário
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

    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <style>
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
<!-- Modal para editar comentários -->
<div class="modal fade" id="editarComentarioModal" tabindex="-1" role="dialog" aria-labelledby="editarComentarioModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarComentarioModalLabel">Editar Comentário</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="processar_comentario.php">
                    <div class="form-group">
                        <label for="comentario">Comentário</label>
                        <textarea class="form-control" id="comentario" name="comentario" rows="3" placeholder="Digite seu comentário" required></textarea>
                    </div>
                    <input type="hidden" id="id_comentario" name="id_comentario">
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </form>
            </div>
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
<script>
function editarComentario(id, texto) {
    document.getElementById('id_comentario').value = id; // Define o id do comentário
    document.getElementById('comentario').value = texto; // Define o texto do comentário
    $('#editarComentarioModal').modal('show'); // Exibe o modal
}
</script>
