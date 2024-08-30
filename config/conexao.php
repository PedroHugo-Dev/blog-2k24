<?php
// Definindo as constantes para a conexão com o banco de dados
if (!defined('DB_CONFIG')) {
    define('DB_CONFIG', [
        'host' => 'localhost',
        'dbname' => 'new_agenda_full',
        'user' => 'root',
        'pass' => 'bdjmf'
    ]);
}

// Criando a conexão com o banco de dados usando PDO
try {
    $dsn = 'mysql:host=' . DB_CONFIG['host'] . ';dbname=' . DB_CONFIG['dbname'];
    $conect = new PDO($dsn, DB_CONFIG['user'], DB_CONFIG['pass']);
    $conect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "<strong>ERRO DE PDO = </strong>" . $e->getMessage();
}
