<?php
include_once('../config/conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_post = filter_input(INPUT_POST, 'id_post', FILTER_VALIDATE_INT);

    if ($id_post) {
        try {
            $deletePost = "DELETE FROM post WHERE id_post = :id_post";
            $stmt = $conect->prepare($deletePost);
            $stmt->bindParam(':id_post', $id_post, PDO::PARAM_INT);
            $stmt->execute();

            // Atualiza a página atual
            header("Refresh:0");
            exit();
        } catch (PDOException $e) {
            error_log("ERRO AO DELETAR POST: " . $e->getMessage());
        }
    }
}
?>
