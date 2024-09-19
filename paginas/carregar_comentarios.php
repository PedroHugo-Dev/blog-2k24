<?php
include_once('../config/conexao.php'); // Inclua seu arquivo de conexão com o banco de dados

$id_post = isset($_GET['id_post']) ? (int)$_GET['id_post'] : 0;
$query = "SELECT * FROM comentario WHERE id_post = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id_post);
$stmt->execute();
$result = $stmt->get_result();

$comments = [];
while ($row = $result->fetch_assoc()) {
    $comments[] = $row;
}

echo json_encode($comments);
?>
