<?php
session_start();
include_once('../config/conexao.php');

// Verificar se a requisição é do tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizar e validar dados recebidos
    $id_post = filter_input(INPUT_POST, 'id_post', FILTER_VALIDATE_INT);
    $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_STRING);
    $corpo = filter_input(INPUT_POST, 'corpo', FILTER_SANITIZE_STRING);

    // Verificar se os dados são válidos
    if ($id_post && $titulo && $corpo) {
        try {
            // Atualizar o post no banco de dados
            $updatePost = "UPDATE post SET titulo = :titulo, corpo = :corpo WHERE id_post = :id_post";
            $stmt = $conect->prepare($updatePost);
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':corpo', $corpo);
            $stmt->bindParam(':id_post', $id_post, PDO::PARAM_INT);
            $stmt->execute();

            // Retornar uma resposta JSON de sucesso
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            // Log do erro e resposta JSON de erro
            error_log("ERRO AO ATUALIZAR POST: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar o post.']);
        }
    } else {
        // Retornar erro se os dados não são válidos
        echo json_encode(['success' => false, 'message' => 'Dados inválidos.']);
    }
} else {
    // Retornar erro se não for um POST
    echo json_encode(['success' => false, 'message' => 'Método inválido.']);
}
