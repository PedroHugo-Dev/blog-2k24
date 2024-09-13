<?php
// processar_post.php
include '../config/conexao.php'; // Inclua seu arquivo de conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebe os dados do formulário
    $titulo = htmlspecialchars($_POST['titulo']);
    $descricao = htmlspecialchars($_POST['descricao']);
    $id_topico = (int)$_POST['assunto'];
    $id_user = 22; // ID do usuário logado, deve ser dinâmico no caso real
    $data_criacao = date('Y-m-d H:i:s');

    // Valores padrão para novos posts
    $numero_likes = '0';
    $numero_deslikes = '0';
    $numero_comentarios = '0';
    
    // Prepara a consulta SQL para inserir o post
    $sql = "INSERT INTO post (id_topico, id_user, titulo, corpo, data_criacao, numero_likes, numero_deslikes, numero_comentarios, assunto)
            VALUES (:id_topico, :id_user, :titulo, :descricao, :data_criacao, :numero_likes, :numero_deslikes, :numero_comentarios, 
            (SELECT nome FROM topico WHERE id_topico = :id_topico))";

    $stmt = $conect->prepare($sql);

    // Bind dos parâmetros
    $stmt->bindParam(':id_topico', $id_topico);
    $stmt->bindParam(':id_user', $id_user);
    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':descricao', $descricao);
    $stmt->bindParam(':data_criacao', $data_criacao);
    $stmt->bindParam(':numero_likes', $numero_likes);
    $stmt->bindParam(':numero_deslikes', $numero_deslikes);
    $stmt->bindParam(':numero_comentarios', $numero_comentarios);

    // Executa a consulta
    if ($stmt->execute()) {
        echo "Post criado com sucesso!";
    } else {
        echo "Erro ao criar post.";
    }
}
?>
