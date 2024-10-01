<?php
include_once('../../config/conexao.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idComentario = filter_input(INPUT_POST, 'id_comentario', FILTER_SANITIZE_NUMBER_INT);
    $usuarioLogado = $_SESSION['id_user'];
    $adm = $_SESSION['adm'];

    try {
        // Verifica se o usuário é o autor do comentário ou se é administrador
        $queryCheck = "
            SELECT id_user 
            FROM comentario 
            WHERE id_comentario = :idComentario
        ";

        $stmtCheck = $conect->prepare($queryCheck);
        $stmtCheck->bindParam(':idComentario', $idComentario, PDO::PARAM_INT);
        $stmtCheck->execute();
        $comentario = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if ($comentario['id_user'] !== $usuarioLogado || $adm !== 1) {
            // Exclui o comentário
            $queryDelete = "
                DELETE FROM comentario 
                WHERE id_comentario = :idComentario
            ";
            $stmtDelete = $conect->prepare($queryDelete);
            $stmtDelete->bindParam(':idComentario', $idComentario, PDO::PARAM_INT);
            $stmtDelete->execute();

            header("Location: ../home.php"); // Redireciona para a página original
        } else {
            echo "Você não tem permissão para deletar este comentário.";
        }
    } catch (PDOException $e) {
        error_log("ERRO AO DELETAR COMENTÁRIO: " . $e->getMessage());
        echo "Erro ao deletar comentário.";
    }
}
?>
