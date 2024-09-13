<?php
// processar_post.php

// Conectar ao banco de dados
$host = 'localhost';
$dbname = 'blog_new';
$username = 'root'; // substitua conforme necessário
$password = 'bdjmf'; // substitua conforme necessário

try {
    $conect = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erro de conexão: ' . $e->getMessage());
}

// Receber dados do formulário
$titulo = $_POST['titulo'];
$descricao = $_POST['descricao'];
$assunto = $_POST['assunto'];
$id_user = 22; // Defina o ID do usuário conforme necessário

// Inserir post no banco de dados
$query = $conect->prepare("INSERT INTO post (id_topico, id_user, titulo, corpo, numero_likes, numero_deslikes, numero_comentarios, assunto) VALUES (:id_topico, :id_user, :titulo, :corpo, '0', '0', '0', :assunto)");

$query->bindParam(':id_topico', $assunto);
$query->bindParam(':id_user', $id_user);
$query->bindParam(':titulo', $titulo);
$query->bindParam(':corpo', $descricao);
$query->bindParam(':assunto', $assunto);

if ($query->execute()) {
    echo "Post criado com sucesso!";
} else {
    echo "Erro ao criar post.";
}
?>
