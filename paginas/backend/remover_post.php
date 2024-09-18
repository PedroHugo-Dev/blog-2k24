<?php
session_start();
include_once('../../config/conexao.php');

// Verifica se o usuário está logado e a requisição é um POST
if (!isset($_SESSION['loginUser']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php'); // Redireciona se não estiver logado
    exit;
}

$id_post = filter_var($_POST['id_post'], FILTER_SANITIZE_NUMBER_INT);

try {
    // Prepara a consulta para remover o post
    $deletePost = "DELETE FROM post WHERE id_post = :id_post";
    $stmt = $conect->prepare($deletePost);
    $stmt->bindParam(':id_post', $id_post, PDO::PARAM_INT);
    $stmt->execute();

    // Redireciona de volta para a página inicial com uma mensagem de sucesso
    $_SESSION['message'] = 'Post removido com sucesso!';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
} catch (PDOException $e) {
    error_log("Erro ao remover o post: " . $e->getMessage());
    $_SESSION['error'] = 'Erro ao remover o post. Tente novamente.';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}
?>
