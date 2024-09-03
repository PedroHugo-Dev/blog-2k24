<?php
include_once('../includes/header.php');

// Sanitização de entrada
$acao = filter_var(isset($_GET['acao']) ? $_GET['acao'] : 'bemvindo', FILTER_SANITIZE_STRING);

// Definir caminhos em variáveis
$paginas = [
    'bemvindo' => 'conteudo/cadastro_contato.php',
    'editar' => 'conteudo/update_contato.php',
    'perfil' => 'conteudo/perfil.php',
    'relatorio' => 'conteudo/relatorio.php',

    'jogos' => 'conteudo/assunto.php?assunto=jogos',
    'tecnologias' => 'conteudo/assunto.php?assunto=tecnologias',
    'filmes' => 'conteudo/assunto.php?assunto=filmes'
];

// Verificar se a ação existe no array, caso contrário, usar a página padrão
$pagina_incluir = isset($paginas[$acao]) ? $paginas[$acao] : $paginas['bemvindo'];

// Incluir a página correspondente
include_once($pagina_incluir);

include_once('../includes/footer.php');

