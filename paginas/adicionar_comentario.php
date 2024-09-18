<?php
include_once('../includes/header.php');
include_once('../config/conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitização e validação dos dados recebidos
    $idPost = filter_var($_POST['id_post'], FILTER_SANITIZE_NUMBER_INT);
    $nomeUsuario = filter_var($_SESSION['loginUser'], FILTER_SANITIZE_STRING); // Assumindo que o nome do usuário está na sessão
    $textoComentario = filter_var($_POST['texto_comentario'], FILTER_SANITIZE_STRING);

    try {
        // Preparar e executar a inserção do comentário
        $inserirComentario = "
            INSERT INTO comentario (id_post, corpo, id_user, data_criacao)
            VALUES (:idPost, :textoComentario, (
                SELECT id_user
                FROM tb_user
                WHERE email_user = :emailUserLogado
            ), NOW())
        ";

        $resultadoComentario = $conect->prepare($inserirComentario);
        $resultadoComentario->bindParam(':idPost', $idPost, PDO::PARAM_INT);
        $resultadoComentario->bindParam(':textoComentario', $textoComentario, PDO::PARAM_STR);
        $resultadoComentario->bindParam(':emailUserLogado', $nomeUsuario, PDO::PARAM_STR);
        $resultadoComentario->execute();

        // Redireciona de volta para a página onde o comentário foi adicionado
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    } catch (PDOException $e) {
        error_log("ERRO AO ADICIONAR COMENTÁRIO: " . $e->getMessage());
        echo 'Erro ao adicionar comentário.';
    }
}
?>
