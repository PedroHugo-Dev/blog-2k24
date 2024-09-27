<?php
include_once('../config/conexao.php');

// Sanitização de entrada
$acao = filter_var(isset($_GET['assunto']) ? $_GET['assunto'] : 'Teste', FILTER_SANITIZE_STRING);
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$posts_per_page = 5;  // Número de posts por página

// Obtendo os tópicos permitidos do banco de dados
$topicoQuery = $conect->prepare("SELECT DISTINCT nome FROM topico");
$topicoQuery->execute();
$topicos = $topicoQuery->fetchAll(PDO::FETCH_COLUMN);

// Verifica se o tópico solicitado está na lista de tópicos
if (!in_array($acao, $topicos)) {
    $acao = "Teste";
}

// Obtendo o id_topico com base no nome do tópico
$topicoQuery = $conect->prepare("SELECT id_topico FROM topico WHERE nome = :nome");
$topicoQuery->execute(['nome' => $acao]);
$topicoResult = $topicoQuery->fetch(PDO::FETCH_ASSOC);

// Verifica se o tópico existe
if ($topicoResult) {
    $id_topico = $topicoResult['id_topico'];

    // Calculando o offset para a paginação
    $offset = ($page - 1) * $posts_per_page;

    // Consulta os posts relacionados ao id_topico com limite e offset
    $query = $conect->prepare("
    SELECT p.*, u.nome_user 
    FROM post p
    JOIN tb_user u ON p.id_user = u.id_user 
    WHERE p.id_topico = :id_topico 
    ORDER BY p.data_criacao DESC 
    LIMIT :offset, :limit
    ");

    $query->bindParam(':id_topico', $id_topico, PDO::PARAM_INT);
    $query->bindValue(':offset', $offset, PDO::PARAM_INT);
    $query->bindValue(':limit', $posts_per_page, PDO::PARAM_INT);
    $query->execute();
    $posts = $query->fetchAll(PDO::FETCH_ASSOC);
} else {
    $posts = [];
}

// Retorna os posts em formato JSON
echo json_encode($posts);
?>
