  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Editar Contato</h1>
          </div>
          
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
      <?php
      // Inclui o arquivo de conexão com o banco de dados
      include('../config/conexao.php');

      // Verifica se o parâmetro 'id' foi passado via GET
      if (!isset($_GET['id'])) {
          // Se não foi passado, redireciona para a página home.php
          header("Location: home.php");
          exit; // Encerra o script
      }

      // Obtém o valor do parâmetro 'id' e filtra como um inteiro
      $id = filter_input(INPUT_GET, 'id', FILTER_DEFAULT);

      // Prepara e executa a consulta para selecionar o contato com base no 'id'
      $select = "SELECT * FROM tb_contatos WHERE id_contatos=:id";
      try {
          $resultado = $conect->prepare($select);
          $resultado->bindParam(':id', $id, PDO::PARAM_INT);
          $resultado->execute();

          // Verifica se foi encontrado algum contato com o 'id' especificado
          $contar = $resultado->rowCount();
          if ($contar > 0) {
              // Se encontrado, obtém os dados do contato
              $show = $resultado->fetch(PDO::FETCH_OBJ);
              $idCont = $show->id_contatos;
              $nome = $show->nome_contatos;
              $fone = $show->fone_contatos;
              $email = $show->email_contatos;
              $foto = $show->foto_contatos;
          } else {
              // Se nenhum contato foi encontrado, exibe uma mensagem de erro
              echo '<div class="alert alert-danger">Não há dados com o id informado!</div>';
          }
      } catch (PDOException $e) {
          // Em caso de erro na consulta PDO, exibe a mensagem de erro
          echo "<strong>ERRO DE SELECT NO PDO: </strong>" . $e->getMessage();
      }
      ?>
        <div class="row">
          <!-- left column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">editar contato</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" action="" method="post" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Nome</label>
                    <input type="text" class="form-control" name="nome" id="nome" required value="<?php echo $nome; ?>">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Telefone</label>
                    <input type="text" class="form-control" name="telefone" id="telefone" required value="<?php echo $fone; ?>">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Endereço de E-mail</label>
                    <input type="email" class="form-control" name="email" id="email" required value="<?php echo $email; ?>">
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
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" name="upContato" class="btn btn-primary">Finalizar edição do contato</button>
                </div>
              </form>

              <?php
              // Verifica se o formulário foi submetido
              if (isset($_POST['upContato'])) {
                  // Obtém os dados do formulário
                  $nome = $_POST['nome'];
                  $fone = $_POST['telefone'];
                  $email = $_POST['email'];

                  // Verifica se foi feito upload de uma nova foto
                  if (!empty($_FILES['foto']['name'])) {
                      // Define os formatos permitidos para a foto
                      $formatP = array("png", "jpg", "jpeg", "gif");
                      $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);

                      // Verifica se a extensão do arquivo está entre os formatos permitidos
                      if (in_array($extensao, $formatP)) {
                          $pasta = "../img/cont/";
                          $temporario = $_FILES['foto']['tmp_name'];
                          $novoNome = uniqid() . ".{$extensao}";

                          // Move o arquivo temporário para a pasta de destino
                          if (move_uploaded_file($temporario, $pasta . $novoNome)) {
                              // Se o upl-ad foi bem-sucedido, verifica se há uma foto antiga para deletar
                              if ($foto && file_exists($pasta . $foto)) {
                                  unlink($pasta . $foto); // Deleta a foto antiga
                              }
                          } else {
                              $mensagem = "Erro, não foi possível fazer o upload do arquivo!";
                          }
                      } else {
                          echo "Formato inválido"; // Se o formato do arquivo não é permitido, exibe mensagem de erro
                      }
                  } else {
                      $novoNome = $foto; // Se não foi feito upload de nova foto, mantém o nome da foto antiga
                  }

                  // Prepara e executa o comando SQL para atualizar os dados do contato
                  $update = "UPDATE tb_contatos SET nome_contatos=:nome, fone_contatos=:fone, email_contatos=:email, foto_contatos=:foto WHERE id_contatos=:id";
                  try {
                      $result = $conect->prepare($update);
                      $result->bindParam(':id', $id, PDO::PARAM_STR);
                      $result->bindParam(':nome', $nome, PDO::PARAM_STR);
                      $result->bindParam(':fone', $fone, PDO::PARAM_STR);
                      $result->bindParam(':email', $email, PDO::PARAM_STR);
                      $result->bindParam(':foto', $novoNome, PDO::PARAM_STR);
                      $result->execute();

                      // Verifica se a atualização foi bem-sucedida
                      $contar = $result->rowCount();
                      if ($contar > 0) {
                          // Se sim, exibe uma mensagem de sucesso e redireciona após 5 segundos
                          echo '<div class="container">
                                    <div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h5><i class="icon fas fa-check"></i> Ok !!!</h5>
                                        Os dados foram atualizados com sucesso.
                                    </div>
                                </div>';
                          header("Refresh: 1, home.php");
                      } else {
                          // Se não, exibe uma mensagem de erro
                          echo '<div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <h5><i class="icon fas fa-check"></i> Erro !!!</h5>
                                    Não foi possível atualizar os dados.
                                </div>';
                      }
                  } catch (PDOException $e) {
                      // Em caso de erro PDO durante a atualização, exibe a mensagem de erro
                      echo "<strong>ERRO DE PDO= </strong>" . $e->getMessage();
                  }
              }
              ?>
              
            </div>
</div>
            
            <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Dados do Contatos</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0" style="text-align: center; margin-bottom: 98px">

            <?php
            // Verifica se a variável $foto_user é igual a 'avatar-padrao.png'
            if ($show->foto_contatos == 'avatar-padrao.png') {
                // Exibe a imagem do avatar padrão
                echo '<img src="../img/avatar_p/' . $show->foto_contatos . '" alt="' .$show->foto_contatos . '" title="' .$show->foto_contatos . '" style="width: 250px; border-radius: 100%; padding-top: 30px">';
            } else {
                // Exibe a imagem do usuário
                echo '<img src="../img/cont/' .$show->foto_contatos . '" alt="' .$show->foto_contatos . '" title="' .$show->foto_contatos . '" style="width: 250px; border-radius: 100%; padding-top: 30px">';
            }
          ?>              
                <h1 id="nome"><?php echo $nome; ?></h1>
                <strong><?php echo $fone; ?></strong>
                <p><?php echo $email; ?></p>
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
  