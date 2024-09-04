<!-- pagina de bem vindo -->
<?php
include_once('../includes/header.php');

// Sanitização de entrada
$usuario = filter_var(isset($_SESSION['loginUser']) ? $_SESSION['loginUser'] : 'Visitante', FILTER_SANITIZE_STRING);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Bem-vindo ao StarBlog</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Olá, <?php echo htmlspecialchars($nome_user); ?>!</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <p>Estamos felizes em tê-lo de volta ao StarBlog. Explore nossas últimas postagens e descubra novidades interessantes!</p>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (right) -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php
include_once('../includes/footer.php');
?>
