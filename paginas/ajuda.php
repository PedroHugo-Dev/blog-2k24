
<?php
    include_once('../config/conexao.php');
    include_once('../includes/header.php')
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

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

<body>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class='blog-title'>StarBlog | Ajuda</h1>
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
                            <h2 style="color:black">Perguntas Frequentes (FAQ)</h2><br>
                            <h4>Como posso adicionar um comentário?</h4>
                            <p>Para adicionar um comentário, acesse a página principal, preencha o formulário com seu nome e mensagem e clique em "Enviar".</p><br>

                            <h4>Posso editar meu comentário?</h4>
                            <p>Sim, você pode editar seu comentário clicando no botão de um lápis ao lado do seu comentário.</p><br>

                            <h4>Como posso excluir um comentário?</h4>
                            <p>Para excluir um comentário, clique no botão "Excluir" ao lado do seu comentário e confirme a ação.</p><br>
                        </div>          
                    </div>         
                </div>   
            </div>
        </div>
</section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-body">
                            <h2 style="color:black">Tutoriais</h2><br>
                            <h3>Guia Rápido: Como criar posts.</h3>
                            <ol>
                                <li> Acesse a página principal.</li>
                                <li> Adicione um novo post.</li>
                                <li> Edite ou exclua posts e comentários conforme necessário.</li>
                            </ol>
                        </div>          
                    </div>         
                </div>   
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-body">
                            <h2 style="color:black">Contatos</h2><br>
                            <h3><p>Se você precisar de mais ajuda, entre em contato conosco:</h3>
                            <ul>
                                <li> francisco.lima1990@aluno.ce.gov.br</li>
                                <li> geovan.lima@aluno.ce.gov.br</li>
                                <li> caua.souza26@aluno.ce.gov.br</li>
                            </ul>
                        </div>          
                    </div>         
                </div>   
            </div>
        </div>
    </section>





    </div><!-- /.wrapper -->



</body>
</html>

<?php include_once('../includes/footer.php') ?>