<?php
// Inicia o buffer de saída
ob_start();

// Inicia a sessão apenas se ainda não tiver sido iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica se as variáveis de sessão estão definidas
if (!isset($_SESSION['loginUser'])) {
    // Redireciona para a página inicial com a mensagem de acesso negado
    header("Location: ../index.php?acao=negado");
    exit;
}

// Inclui o script de saída
include_once('sair.php'); 
?>
<!DOCTYPE html>
<html lang="pt_br">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Blog</title>
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.dataTables.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.1/css/buttons.dataTables.css">

  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="../plugins/summernote/summernote-bs4.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link rel="stylesheet" href="../dist/css/estilo.css">
  <!-- Custom Styles -->
  <style>
    body {
      background-color: #f0f4f8; /* Cor de fundo leve */
    }
    .navbar, .main-sidebar {
      background-color: #25688E; /* Azul escuro para o fundo */
    }
    .navbar a, .navbar .btn, .main-sidebar .nav-link {
      color: #ffffff; /* Branco para o texto dos links e botões */
    }
    .btn-primary {
      background-color: #ffc107; /* Amarelo para o fundo do botão */
      border-color: #ffc107; /* Amarelo para a borda do botão */
      color: #25688E; /* Azul escuro para o texto do botão */
    }
    .modal-content {
      background-color: #ffffff; /* Branco para o fundo do modal */
    }
    .modal-header, .modal-footer {
      background-color: #25688E; /* Azul escuro para o cabeçalho e rodapé do modal */
      color: #ffffff; /* Branco para o texto do modal */
    }
    .alert {
      color: #25688E; /* Azul escuro para a mensagem de alerta */
      background-color: #f8d7da; /* Fundo da mensagem de alerta */
    }
    .sidebar-dark-primary {
      background-color: #25688E; /* Azul escuro para a barra lateral */
    }
    .brand-link {
      color: #ffffff; /* Branco para o texto do logo */
    }
    .nav-sidebar .nav-link.active {
      background-color: #25688E; /* Azul escuro para o link ativo */
    }
    .nav-sidebar .nav-link:hover {
      background-color: #003d7a; /* Azul mais escuro para o link hover */
    }
    .foto-perfil {
      border-radius: 50%; /* Garantir que a foto do perfil seja circular */
    }
    .card-primary{
      color: #003d7a;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">

</style>

<div class="wrapper">

<?php
// Inclui o arquivo de configuração de conexão com o banco de dados
include_once('../config/conexao.php');

// Obtém o email do usuário logado a partir da sessão
$usuarioLogado = $_SESSION['loginUser'];

// Define a consulta SQL para selecionar todos os campos do usuário com base no email
$selectUser = "SELECT * FROM tb_user WHERE email_user=:emailUserLogado";

try {
    // Prepara a consulta SQL
    $resultadoUser = $conect->prepare($selectUser);
    
    // Vincula o parâmetro :emailUserLogado ao valor da variável $usuarioLogado
    $resultadoUser->bindParam(':emailUserLogado', $usuarioLogado, PDO::PARAM_STR);
    
    // Executa a consulta preparada
    $resultadoUser->execute();

    // Conta o número de linhas retornadas pela consulta
    $contar = $resultadoUser->rowCount();
    
    // Se houver uma ou mais linhas retornadas
    if ($contar > 0) {
        // Obtém a próxima linha do conjunto de resultados como um objeto
        $show = $resultadoUser->fetch(PDO::FETCH_OBJ);
        
        // Atribui os valores dos campos do usuário às variáveis PHP
        $id_user = $show->id_user;
        $foto_user = $show->foto_user;
        $nome_user = $show->nome_user;
        $email_user = $show->email_user;
    } else {
        // Exibe uma mensagem de aviso se não houver dados de perfil
        echo '<div class="alert alert-danger"><strong>Aviso!</strong> Não há dados de perfil :(</div>';
    }
} catch (PDOException $e) {
    // Registra a mensagem de erro no log do servidor em vez de exibi-la ao usuário
    error_log("ERRO DE LOGIN DO PDO: " . $e->getMessage());
    
    // Exibe uma mensagem de erro genérica para o usuário
    echo '<div class="alert alert-danger"><strong>Aviso!</strong> Ocorreu um erro ao tentar acessar os dados do perfil.</div>';
}
?>  

<!-- index.html -->

<nav class="main-header navbar navbar-expand navbar-dark">
  <ul class="navbar-nav">
    <li class="nav-item">
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#criarTopicoModal">
        <i class="fas fa-comment-alt mr-2"></i> Criar Post
      </button>
    </li>
  </ul>
  <form class="form-inline mx-auto w-50">
    <input type="search" class="form-control w-75" placeholder="Pesquisar...">
    <button type="submit" class="btn btn-primary">
      <i class="fas fa-search mr-2"></i> Pesquisar
    </button>
  </form>
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <a href="?sair">
        <button type="button" class="btn btn-primary">
          <i class="fas fa-sign-out-alt mr-2"></i> Sair
        </button>
      </a>
    </li>
  </ul>
</nav>
<?php
include_once('../config/conexao.php');

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Obtém os dados do formulário
  $titulo = $_POST['titulo'];
  $descricao = $_POST['descricao'];
  $assunto = $_POST['assunto'];

  // Verifica se os campos estão preenchidos
  if (!empty($titulo) && !empty($descricao) && !empty($assunto)) {
      // Recupera o id_topico com base no assunto fornecido
      $stmt = $conect->prepare("SELECT id_topico FROM topico WHERE nome = :assunto");
      $stmt->bindParam(':assunto', $assunto, PDO::PARAM_STR);
      $stmt->execute();
      $id_topico = $stmt->fetchColumn();

      // Verifica se id_topico foi encontrado
      if ($id_topico !== false) {
          // Prepara e executa a consulta SQL para inserir o post
          $query = $conect->prepare("INSERT INTO post (id_topico, id_user, titulo, corpo, data_criacao, data_modificacao, numero_likes, numero_deslikes, numero_comentarios, assunto) VALUES (:id_topico, :id_user, :titulo, :corpo, NOW(), NOW(), '0', '0', '0', :assunto)");
          $query->bindParam(':id_topico', $id_topico, PDO::PARAM_INT);
          $query->bindParam(':id_user', $_SESSION['loginUser'], PDO::PARAM_INT);
          $query->bindParam(':titulo', $titulo, PDO::PARAM_STR);
          $query->bindParam(':corpo', $descricao, PDO::PARAM_STR);
          $query->bindParam(':assunto', $assunto, PDO::PARAM_STR);

          // Executa a consulta SQL
          if ($query->execute()) {
              // Redireciona para a página de sucesso
              header('Location: ../index.php?acao=sucesso');
              exit;
          } else {
              // Exibe uma mensagem de erro
              echo 'Erro ao criar o post!';
          }
      } else {
          // Exibe uma mensagem de erro se o assunto não for encontrado
          echo 'Assunto não encontrado!';
      }
  } else {
      // Exibe uma mensagem de erro se os campos não estiverem preenchidos
      echo 'Preencha todos os campos!';
  }
}

?>

<!-- Modal para criar tópico -->
<div class="modal fade" id="criarTopicoModal" tabindex="-1" role="dialog" aria-labelledby="criarTopicoModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="criarTopicoModalLabel">Criar Post</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action ="processar_post.php">
          <div class="form-group">
            <label for="titulo">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Digite o título do Post" required>
          </div>
          <div class="form-group">
            <label for="descricao">Descrição</label>
            <textarea class="form-control" id="descricao" name="descricao" rows="3" placeholder="Digite a descrição do Post" required></textarea>
          </div>

          <?php 
                $query = $conect->prepare("SELECT * FROM topico ORDER BY nome DESC");
                $query->execute();
                $topicos = $query->fetchAll(PDO::FETCH_ASSOC);
          ?>
          <div class="form-group">
            <label for="assunto">Assunto</label>
            <select class="form-control" id="assunto" name="assunto" required>
              <option value="" disabled selected>Selecione o assunto</option>

              <?php foreach ($topicos as $topico): ?>
                     <option value = <?= htmlspecialchars($topico['nome']); ?> > <?= htmlspecialchars($topico['nome']); ?> </option>
              <?php endforeach; ?>

            </select>
          </div>
          <button type="submit" class="btn btn-primary">Criar Tópico</button>
        </form>
      </div>
    </div>
  </div>
</div>

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <span class="brand-text font-weight-light">Blog||JMF</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <?php
              // Verifica se a variável $foto_user é igual a 'avatar-padrao.png'
              if ($foto_user == 'avatar-padrao.png') {
                  // Exibe a imagem do avatar padrão
                  echo '<img src="../img/avatar_p/' . $foto_user . '" alt="' . $foto_user . '" title="' . $nome_user . '" style="width: 40px; border-radius: 100%;" class="foto-perfil">';
              } else {
                  // Exibe a imagem do usuário
                  echo '<img src="../img/user/' . $foto_user . '" alt="' . $foto_user . '" title="' . $nome_user . '" style="width: 40px; border-radius: 100%;" class="foto-perfil">';
              }
            ?>
          </div>

          <script>
            const imgPerfil = document.querySelector('.foto-perfil');
            imgPerfil.addEventListener('click', function() {
              window.location.href = 'home.php?acao=perfil';
            });
          </script>
          <div class="info">
            <a href="#" class="d-block"><?php echo $nome_user; ?></a>
          </div>
        </div>
      <!-- Sidebar Menu -->
      <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="home.php?acao=bemvindo" class="nav-link">
              <p>
                Home
              </p>
            </a>
          </li>
        </ul>
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->



          <li class="nav-item menu-open">
            <a href="#" class="nav-link">
              <p>
              Categorias
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">

            <li class="nav-item">
                <a href="./assunto.php" class="nav-link">
                  <p>Jogos</p>
                </a>
              </li>

              <li class="nav-im">
                <a href="./assunto.php?assunto=filmes" class="nav-link">
                  <p>Filmes</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="./assunto.php?assunto=tecnologias" class="nav-link">
                  <p>Tecnologias</p>
                </a>
              </li>

              <?php 
                $query = $conect->prepare("SELECT * FROM topico ORDER BY nome DESC");
                $query->execute();
                $topicos = $query->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <?php foreach ($topicos as $topico): ?>
                  <li class="nav-item">
                    <a href="./assunto.php?id_topico=<?= $topico['nome'] ?>" class="nav-link">
                      <p><?= htmlspecialchars($topico['nome']); ?></p>
                    </a>
                  </li>
              <?php endforeach; ?>
            </ul>
          </li>
          <li class="nav-item menu-open">
            <a href="#" class="nav-link">
              <p>
              Recursos
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index1.php" class="nav-link">
                  <p>Sobre o site</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index2.php" class="nav-link">
                  <p>Ajuda</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index3.php" class="nav-link">
                  <p>Contatos</p>
                </a>
              </li>
              <?php ///////// ?>
              <li class="nav-item">
                <a href="../paginas/index3.php" class="nav-link">
                  <p>Painel de administrador</p>
                </a>
              </li>

            </ul>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>