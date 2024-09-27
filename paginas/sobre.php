
<?php

use Masterminds\HTML5;

include_once('../config/conexao.php');
include_once('../includes/header.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<style> 
    /* Estilo para o título do blog */
    .blog-title {
        font-size: 28px; /* Aumenta o tamanho da fonte */
        font-weight: 700; /* Deixa o texto em negrito */
        color: #343a40; /* Cor do texto (similar ao restante) */
        text-transform: uppercase; /* Transforma o texto em maiúsculas */
        border-bottom: 2px solid #ffc107; /* Borda inferior para destaque */
        padding-bottom: 10px; /* Espaçamento abaixo do título */
    }

    /* Melhorando a responsividade */
    @media (max-width: 768px) {
        .blog-title {
            font-size: 24px; /* Reduz o tamanho em telas menores */
        }
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class='blog-title'>StarBlog | Sobre o Site</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-body">
                        <h1>Sistema de Comentários e Recados em PHP e PDO</h1>
                        <br>
                        <p><strong>Desenvolvendo um Sistema Dinâmico com Banco de Dados:</strong><br>
                           Estamos criando um sistema interativo que permite aos
                           usuários postarem recados e comentários. Esses dados são 
                           armazenados em um banco de dados utilizando PHP e PDO (PHP Data Objects), proporcionando uma experiência dinâmica e segura.</p>
                        <p><strong>Objetivos:</strong><br>
                        Um sistema de recados e comentários é fundamental para fomentar a interação e o engajamento dos usuários. Ele permite feedbacks,
                         troca de informações e a construção de uma comunidade em um ambiente digital. Essa interação é essencial para o sucesso de qualquer plataforma que visa conectar pessoas.</p>
                         <p><strong>Uso de PDO para Interação Segura com o Banco de Dados:</strong><br>
                         A utilização do PDO garante uma camada de abstração e segurança ao acessar o banco de dados. Esta extensão do PHP protege contra injeções de SQL e simplifica a manutenção do código. Com a utilização de declarações preparadas e parâmetros, conseguimos proteger o sistema contra vulnerabilidades comuns e realizar operações de forma segura e eficiente.</p>
                         <p><strong>Segurança e Boas Práticas:</strong><br>
                         Para garantir a segurança e integridade do sistema, seguimos algumas boas práticas:
                         <ul>
                             <li>Prepared Statements: Utilização de declarações preparadas para prevenir injeções de SQL.</li>
                             <li>Validação e Sanitização de Entradas: As entradas de nome e mensagem serão validadas e sanitizadas para garantir que apenas dados limpos sejam armazenados.</li>
                             <li>Uso de Senhas Seguras: Caso haja um sistema de autenticação, utilizaremos senhas seguras e práticas recomendadas para armazenamento.</li>
                         </ul>
                        </p>
                </div>          
            </div>         
            </div>   
        </div>
    </div>
    </div>
</section>
<?php
include_once('../includes/footer.php')?>
</body>
</html>
<?php include_once('../includes/footer.php') ?>