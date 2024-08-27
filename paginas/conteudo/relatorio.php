  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <setion class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">

          
        </div>
      </div><!-- /.container-fluid -->
    </setion>
    <section class="content">
      <div class="container-fluid">
        
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Lista de contatos</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example" class="display nowrap" style="width:100%”">
                  <thead>
                  <tr>
                    <th style="width: 5%; text-align:center">#</th>
                    <th style="text-align:center">Foto</th>
                    <th>Nome</th>
                    <th>Telefone</th>
                    <th>E-mail</th>
                    <th>Ações</th>
                  </tr>
                  </thead>
                  <tbody>

                  <?php
                   // Consulta SQL para selecionar os contatos do usuário atual
                    $select = "SELECT * FROM tb_contatos ORDER BY id_contatos DESC ";
              
                  try{
                  // Prepara a consulta SQL com o parâmetro :id_user
                    $result = $conect->prepare($select);

                    $cont = 1; // Inicializa o contador de linhas

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
                      <td>
              <?php
            // Verifica se a variável $foto_user é igual a 'avatar-padrao.png'
            if ($show->foto_contatos == 'avatar-padrao.png') {
                // Exibe a imagem do avatar padrão
                echo '<img src="../img/avatar_p/' . $show->foto_contatos . '" alt="' .$show->foto_contatos . '" title="' .$show->foto_contatos . '" style="width: 40px; border-radius: 100%;">';
            } else {
                // Exibe a imagem do usuário
                echo '<img src="../img/cont/' .$show->foto_contatos . '" alt="' .$show->foto_contatos . '" title="' .$show->foto_contatos . '" style="width: 40px; border-radius: 100%;">';
            }
          ?></td>
                      <td><?php echo $show->nome_contatos; ?></td>
                      <td><?php echo $show->fone_contatos; ?></td>
                      <td><?php echo $show->email_contatos; ?></td>
                      
                      <td>
                      <div class="btn-group">
                        <a href="home.php?acao=editar&id=<?php echo $show->id_contatos;?>" class="btn btn-success" title="Editar Contato"><i class="fas fa-user-edit"></i></button>
                        <a href="conteudo/del-contato.php?idDel=<?php echo $show->id_contatos;?>" onclick="return confirm('Deseja remover o contato?')" class="btn btn-danger" title="Remover Contato"><i class="fas fa-user-times"></i></a>
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
                  <tfoot>
                  <tr>
                    <th>#</th>
                    <th>Foto</th>
                    <th>Nome</th>
                    <th>Telefone</th>
                    <th>E-mail</th>
                    <th>Ações</th>
                  </tr>
                  </tfoot>
                </table>
               
                </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          
          
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
      
      </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  