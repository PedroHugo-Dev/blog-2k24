<?php
include_once('../config/conexao.php');

// Sanitização de entrada
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$posts_per_page = 5;  // Número de posts por página

// Calculando o offset para a paginação
$offset = ($page - 1) * $posts_per_page;

// Consulta para obter posts aleatórios com o nome do tópico e do usuário
$query = $conect->prepare("
    SELECT p.*, t.nome AS topico_nome, u.nome_user AS usuario_nome
    FROM post p
    JOIN topico t ON p.id_topico = t.id_topico
    JOIN tb_user u ON p.id_user = u.id_user
    ORDER BY RAND() 
    LIMIT :offset, :limit
");

$query->bindValue(':offset', $offset, PDO::PARAM_INT);
$query->bindValue(':limit', $posts_per_page, PDO::PARAM_INT);
$query->execute();
$posts = $query->fetchAll(PDO::FETCH_ASSOC);

// Retorna os posts em formato JSON
echo json_encode($posts);
?>
