  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Cadastro de Contatos</h1>
          </div>
          
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-4">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Cadastrar contato</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" action="" method="post" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Nome</label>
                    <input type="text" class="form-control" name="nome" id="nome" required placeholder="Digite o nome de contato">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Telefone</label>
                    <input type="text" class="form-control" name="telefone" id="telefone" required placeholder="(00) 00000-0000">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Endereço de E-mail</label>
                    <input type="email" class="form-control" name="email" id="email" required placeholder="Digite um e-mail">
                  </div>
                  
                  <div class="form-group">
                    <label for="exampleInputFile">Foto do contato</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" name="foto" id="foto">
                        <label class="custom-file-label" for="exampleInputFile">Arquivo de imagem</label>
                      </div>
                      
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="hidden" class="custom-file-input" name="id_user" id="id_user" value="<?php echo $id_user ?>">
                      </div>
                    </div>
                  </div>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1" required>
                    <label class="form-check-label" for="exampleCheck1">Autorizo o cadastro do meu contato</label>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" name="botao" class="btn btn-primary">Cadastrar Contato</button>
                </div>
              </form>

              <?php
              // Inclui o arquivo de conexão com o banco de dados
      include('../config/conexao.php');
      // Verifica se o formulário foi submetido
      if (isset($_POST['botao'])) {
          // Recupera os valores do formulário
          $nome = $_POST['nome'];
          $telefone = $_POST['telefone'];
          $email = $_POST['email'];
          $id_usuario = $_POST['id_user'];

          // Define os formatos de imagem permitidos
          $formatP = array("png", "jpg", "jpeg", "JPG", "gif");

          // Verifica se a imagem foi enviada e se é válida
          if (isset($_FILES['foto'])) {
              $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);

              // Verifica se o formato da imagem é permitido
              if (in_array($extensao, $formatP)) {
                  // Define o diretório para upload da imagem
                  $pasta = "../img/cont/";

                  // Move o arquivo temporário para o diretório de upload
                  $temporario = $_FILES['foto']['tmp_name'];
                  $novoNome = uniqid() . ".$extensao";

                  if (move_uploaded_file($temporario, $pasta . $novoNome)) {
                      // Se o upload for bem-sucedido, define o nome do arquivo como o nome da imagem
                      $foto = $novoNome;
                  } else {
                      // Se o upload falhar, exibe mensagem de erro e define o avatar padrão
                      echo "Erro, não foi possível fazer o upload do arquivo!";
                      $foto = 'avatar_padrao.png';
                  }
              } else {
                  // Se o formato da imagem não for permitido, exibe mensagem de erro e define o avatar padrão
                  echo "Formato Inválido";
                  $foto = 'avatar-padrao.png';
              }
          } else {
              // Se não houver imagem enviada, define o avatar padrão
              $foto = 'avatar-padrao.png';
          }

          // Prepara a consulta SQL para inserir os dados no banco de dados
          $cadastro = "INSERT INTO tb_contatos (nome_contatos, fone_contatos, email_contatos, foto_contatos, id_user) VALUES (:nome, :telefone, :email, :foto, :id_user)";

          try {
              // Prepara a consulta SQL com os parâmetros
              $result = $conect->prepare($cadastro);
              $result->bindParam(':nome', $nome, PDO::PARAM_STR);
              $result->bindParam(':telefone', $telefone, PDO::PARAM_STR);
              $result->bindParam(':email', $email, PDO::PARAM_STR);
              $result->bindParam(':foto', $foto, PDO::PARAM_STR);
              $result->bindParam(':id_user', $id_usuario, PDO::PARAM_INT); // Adicionando o id_usuario

              
              // Executa a consulta SQL
              $result->execute();

              // Verifica se a inserção foi bem-sucedida
              $contar = $result->rowCount();
              if ($contar > 0) {
                  // Se a inserção for bem-sucedida, exibe mensagem de sucesso
                  echo '<div class="container">
                          <div class="alert alert-success alert-dismissible">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                          <h5><i class="icon fas fa-check"></i> OK!</h5>
                          Dados inseridos com sucesso !!!
                        </div>
                      </div>';
                  header("Refresh: 5, home.php");
              } else {
                  // Se a inserção falhar, exibe mensagem de erro
                  echo '<div class="container">
                        <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-check"></i> Erro!</h5>
                        Dados não inseridos !!!
                      </div>
                    </div>';
                  header("Refresh: 5, home.php");
              }
          } catch (PDOException $e) {
              // Exibe mensagem de erro se ocorrer um erro de PDO
              echo "<strong>ERRO DE PDO= </strong>" . $e->getMessage();   
            }
          }     
                    ?>
                    
            </div>
</div>
            
            <div class="col-md-8">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Contatos Recentes</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Nome</th>
                      <th>Telefone</th>
                      <th>E-mail</th>
                      <th style="width: 40px">Ações</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                   // Consulta SQL para selecionar os contatos do usuário atual
                    $select = "SELECT * FROM tb_contatos WHERE id_user = :id_user ORDER BY id_contatos DESC LIMIT 6";
              
                  try{
                  // Prepara a consulta SQL com o parâmetro :id_user
                    $result = $conect->prepare($select);

                    $cont = 1; // Inicializa o contador de linhas

                    $result->bindParam(':id_user', $id_user, PDO::PARAM_INT); // Adicionando o id_user

                    // Executa a consulta SQL
                    $result->execute();

                    // Verifica se a consulta retornou algum resultado
                    $contar = $result->rowCount();

                    if($contar > 0){
                      // Itera sobre cada linha de resultado da consulta
                      while ($show = $result->FETCH(PDO::FETCH_OBJ)){

                      
                   ?> 
                    <tr>
                      <td><?php echo $cont++; ?></td>
                      <td><?php echo $show->nome_contatos; ?></td>
                      <td><?php echo $show->fone_contatos; ?></td>
                      <td><?php echo $show->email_contatos; ?></td>

                     
                      <td>
                      <div class="btn-group">
                        <a href="home.php?acao=editar&id=<?php echo $show->id_contatos;?>" class="btn btn-success" title="Editar Contato"><i class="fas fa-user-edit"></i></button>
                        <a href="conteudo/del-contato.php?idDel= <?php echo $show->id_contatos;?>" onclick="return confirm('Deseja remover o contato?')" class="btn btn-danger" title="Remover Contato"><i class="fas fa-user-times"></i></a>
                      </div>
                      </td>
                    </tr>
                  <?php
                }
              }
            else{
              // Se a consulta não retornar resultados, exibe uma mensagem
              echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button>
              <strong>Não ha contatos!</strong>(</div>';
              }

            }catch(Exception $e){
              // Exibe a mensagem de erro de PDO
              echo '<strong>ERRO DE PDO= </strong>' . $e->getMessage();
            }
              ?>
                
                                       
                  </tbody>
                </table>
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
  