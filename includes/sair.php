<?php

if(isset($_REQUEST['sair'])){
    session_destroy();
    header("Location: ../index.php?acao=sair");
}

if(isset($_REQUEST['logar'])){
    header("Location: ../index.php?redirecionamento=true");
}

if(isset($_REQUEST['criarConta'])){
    header("Location: ../cad_user.php?redirecionamento=true");
}