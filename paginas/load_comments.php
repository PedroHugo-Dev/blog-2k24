<?php
include_once('../config/conexao.php');

// Sanitização de entrada
$idPost = filter_var(isset($_GET['id_post']) ? $_GET['id_post'] : 0, FILTER_VALIDATE_INT);

if ($idPost) {
    // Consulta os comentários relacionados ao id_post
    $query = $conect->prepare("
        SELECT c.*, u.nome_user
        FROM comentario c
        JOIN tb_user u ON c.id_user = u.id_user
        WHERE c.id_post = :id_post
        ORDER BY c.data_criacao ASC
    ");
    $query->bindParam(':id_post', $idPost, PDO::PARAM_INT);
    $query->execute();
    $comentarios = $query->fetchAll(PDO::FETCH_ASSOC);
} else {
    $comentarios = [];
}

// Retorna os comentários em formato JSON
echo json_encode($comentarios);
?>
