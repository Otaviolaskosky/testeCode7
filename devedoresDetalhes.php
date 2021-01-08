
<?php
include 'variaveisMongoDB.php';

$idCliente = $_GET['idCliente'];

include "devedores.class.php";

$classeDevedores = new devedores;

$classeDevedores->registraFormularioDividas();

$dividas = $classeDevedores->consultaDividasBD(null, $idCliente);
?>

<!DOCTYPE html>


<html lang="en">

    <!DOCTYPE html>
    <html lang="en">

        <?php include "head.php" ?>

        <body id="page-top">

            <div id="wrapper">

                <?php include "sidebar.php" ?>

                <div id="content-wrapper" class="d-flex flex-column">

                    <div id="content">

                        <?php include 'topbar.php' ?>

                        <div class="container-fluid">

                            <div class="row">
                                <div class="col-sm-12">
                                    <h1 class="h3 mb-2 text-gray-800">Devedores</h1>
                                </div>
                            </div>
                            <br>

                            <form method="post" action="" role="form">

                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Lista de dívida(s) do Devedor <?php echo $dividas[$idCliente][0]['nomeCliente']; ?></h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                <thead>
                                                    <tr>
                                                        <th>Valor</th>
                                                        <th>Data</th>
                                                        <th>Motivo</th>
                                                        <th style="width: 60px; text: center">Editar</th>
                                                        <th style="width: 60px; text: center">Excluir</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php
                                                    foreach ($dividas[$idCliente] as $divida) {
                                                        ?>

                                                        <tr>
                                                            <td><?php echo $divida['valor'] ?></td>
                                                            <td><?php echo date('d/m/Y', strtotime($divida['data'])); ?></td>
                                                            <td><?php echo $divida['motivo'] ?></td>


                                                            <td style="text-align: center;margin: auto"><a href="dividasDevedoresCadastro.php?idDivida=<?php echo $divida['_id'] ?>"><img src="img/editar.png" width="40%" ></a></td>
                                                            <td style="text-align: center;margin: auto">
                                                                <a  href="#" class="linkExclusaoDivida" data-toggle="modal" data-target="#deletaDividaModal" idDivida="<?php echo $divida['_id'] ?>">
                                                                    <img src="img/delete.png" width="30%" >
                                                                </a>
                                                            </td>

                                                            <?php
                                                        }
                                                        ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <footer class="sticky-footer bg-white">
                                        <div class="row ">
                                            <div class="col-md-12 text-center">
                                                <a href="devedores.php" class="btn btn-outline btn-primary">Voltar</a>
                                            </div>
                                        </div>
                                    </footer>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>

                <form method="post" action="" role="form">
                    <!-- Modal para Exclusão -->
                    <div class="modal fade" id="deletaDividaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                         aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Deletar Divida</h5>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">Tem certeza que deseja deletar esta dívida?.</div>

                                <!--Campos ocultos:-->
                                <!--Tipo da tela para identificar que é exclusão: -->
                                <input type="hidden" name="tipoTela" value="exclusao">

                                <!--Campo para armazenar o id da divida:-->
                                <input type="hidden" name="idDividaModal" id="idDividaModal">

                                <div class="modal-footer">
                                    <button class="btn btn-secondary" type="submit" formaction="devedores.php" type="button" >Sim</button>
                                    <a class="btn btn-primary" data-dismiss="modal" href="#">Não</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <?php include 'importaJavaScript.php' ?>

                <!-- Comandos jQuery: -->
                <script>
                    $(document).ready(function () {

                        

                    });
                </script>

        </body>


    </html>