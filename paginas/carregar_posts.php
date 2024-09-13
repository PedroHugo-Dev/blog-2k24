<?php
include '../config/conexao.php'; // Inclua seu arquivo de conexão com o banco de dados

// Recebe o número de posts a serem retornados e a página atual
$limit = 5;
$offset = isset($_POST['offset']) ? (int)$_POST['offset'] : 0;

// Consulta SQL para buscar posts aleatoriamente
$sql = "SELECT * FROM post
        ORDER BY RAND()
        LIMIT :limit OFFSET :offset";

$stmt = $conect->prepare($sql);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retorna os posts como JSON
echo json_encode($posts);
?>
