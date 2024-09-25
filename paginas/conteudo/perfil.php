<style>
  .card-header2{
    background-color: #25688E;
  }
  .card-title2{
    color: #ffffff;
    margin-top: 6px;
    text-align: center;
  }

</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header2">
                <h3 class="card-title2"><b>Editar informações de <?php echo $nome_user ?></b></h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" action="" method="post" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Nome</label>
                    <input type="text" class="form-control" name="nome" id="nome" required value="<?php echo $nome_user; ?>">
                  </div>
                  
                  <div class="form-group">
                    <label for="exampleInputEmail1">Endereço de E-mail</label>
                    <input type="email" class="form-control" name="email" id="email" required value="<?php echo $email_user; ?>">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Senha</label>
                    <input type="password" class="form-control" name="senha" id="telefone" value="" placeholder="**************************">
                  </div>
                  
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" name="upPerfil" class="btn btn-primary">Salvar modificações</button>
                </div>
              </form>
             <?php
                include('../config/conexao.php'); // Inclui o arquivo de conexão com o banco de dados

                // Verifica se o formulário foi enviado
                if (isset($_POST['upPerfil'])) {
                  // Recebe os dados do formulário
                  $nome = $_POST['nome'];
                  $email = $_POST['email'];
                  $senha_nova = $_POST['senha'];

                  // Obter os valores antigos do banco de dados
                  $query = "SELECT email_user, senha_user FROM tb_user WHERE id_user=:id";
                  $stmt = $conect->prepare($query); // Prepara a consulta SQL
                  $stmt->bindParam(':id', $id_user, PDO::PARAM_STR); // Vincula o parâmetro ID do usuário
                  $stmt->execute(); // Executa a consulta
                  $row = $stmt->fetch(PDO::FETCH_ASSOC); // Busca os resultados como um array associativo
                  $email_antigo = $row['email_user']; // Armazena o email antigo
                  $senha_antiga = $row['senha_user']; // Armazena a senha antiga

                  // Verificar se existe imagem para fazer o upload
                  if (!empty($_FILES['foto']['name'])) {
                    $formatP = array("png", "jpg", "jpeg", "gif"); // Formatos permitidos para upload
                    $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION); // Obtém a extensão do arquivo

                    // Verifica se a extensão do arquivo está nos formatos permitidos
                    if (in_array($extensao, $formatP)) {
                      $pasta = "../img/user/"; // Define o diretório de upload
                      $temporario = $_FILES['foto']['tmp_name']; // Caminho temporário do arquivo
                      $novoNome = uniqid() . ".{$extensao}"; // Gera um nome único para o arquivo

                      // Excluir a imagem antiga se ela existir
                      if (file_exists($pasta . $foto_user)) {
                        unlink($pasta . $foto_user); // Remove o arquivo antigo
                      }
                      // Move o novo arquivo para o diretório de upload
                      if (move_uploaded_file($temporario, $pasta . $novoNome)) {
                        // Sucesso no upload da nova imagem
                      } else {
                        $mensagem = "Erro, não foi possível fazer o upload do arquivo!"; // Mensagem de erro
                      }
                    }else {
                            echo "Formato inválido"; // Mensagem de erro para formato de arquivo inválido
                        }
                  }else{
                    $novoNome = $foto_user;
                  }

                  // Verificar se a senha foi alterada
                  if (!empty($senha_nova)) {
                    $senha = password_hash($senha_nova, PASSWORD_DEFAULT); // Hash seguro para a nova senha
                  }else{
                    $senha = $senha_antiga; // Mantém a senha antiga
                  }

                  // Atualizar o banco de dados
                  $update = "UPDATE tb_user SET foto_user=:foto, nome_user=:nome, email_user=:email, senha_user=:senha WHERE id_user=:id";
                  try {
                    // Prepara a consulta de atualização
                    $result = $conect->prepare($update);
                    $result->bindParam(':id', $id_user, PDO::PARAM_STR); // Vincula o ID do usuário
                    $result->bindParam(':foto', $novoNome, PDO::PARAM_STR); // Vincula o novo nome da foto
                    $result->bindParam(':nome', $nome, PDO::PARAM_STR); // Vincula o nome do usuário
                    $result->bindParam(':email', $email, PDO::PARAM_STR); // Vincula o email do usuário
                    $result->bindParam(':senha', $senha, PDO::PARAM_STR); // Vincula a senha codificada do usuário
                    $result->execute(); // Executa a consulta

                    $contar = $result->rowCount(); // Conta o número de linhas afetadas
                    if($contar > 0){
                      echo '<div class="container">
                      <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-check"></i> Ok !!!</h5>
                        Perfil atualizado com sucesso.
                      </div>
                    </div>';

                    // Verificar se tanto o email quanto a senha foram alterados
                    if ($email !== $email_antigo || $senha !== $senha_antiga) {
                      header("Location: ?sair"); // Redireciona para sair se email ou senha foram alterados
                      exit(); // Garante que o redirecionamento ocorra
                  } else {
                      header("Refresh: 3; home.php?acao=perfil"); // Redireciona de volta ao perfil após 3 segundos
                      exit(); // Garante que o redirecionamento ocorra
                  }
                    }else{
                      // Mensagem de erro se nenhum dado foi atualizado
                      echo '<div class="alert alert-danger alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h5><i class="icon fas fa-times"></i> Erro !!!</h5>
                      Perfil não foi atualizado.
                    </div>';
                    }
                  } catch (PDOException $e) {
                    // Mensagem de erro para exceções PDO
                    echo "<strong>ERRO DE PDO= </strong>" . $e->getMessage();
                  }
                }
             ?>
            </div>
</div>
            
            <div class="col-md-6">
            <div class="card">
              <div class="card-header2">
                <h3 class="card-title2"><b>Foto de <?php echo $nome_user ?></b></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0" style="text-align: center; margin-bottom: 98px">
              

                
              <?php
            // Verifica se a variável $foto_user é igual a 'avatar-padrao.png'
            if ($show->foto_user == 'avatar-padrao.png') {
                // Exibe a imagem do avatar padrão
                echo '<img src="../img/avatar_p/' . $show->foto_user . '" alt="' . $show->foto_user . '" title="' . $show->foto_user . '" style="width: 141px; border-radius: 100%; padding-top:15px; margin-bottom: 20px;">';
            } else {
                // Exibe a imagem do usuário
                echo '<img src="../img/user/' . $show->foto_user . '" alt="' . $show->foto_user . '" title="' . $show->foto_user . '" style="width: 250px; border-radius: 100%; padding-top:15px">';
            }
            ?>
            <div class="form-group">
                    <label for="exampleInputFile2">Avatar do usuário</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" name="foto" id="foto">
                        <label class="custom-file-label" style="margin-left:20px; width: 764px;" for="exampleInputFile">Arquivo de imagem</label>
                      </div>
                      
                    </div>
                  </div>
                
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
            </div>

          </div>
          <!--/.col (right) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php include_once('../includes/footer.php'); ?>