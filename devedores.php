<?php
include "devedores.class.php";

$classeDevedores = new devedores;

$classeDevedores->registraFormularioDividas();

$devedores = $classeDevedores->consultaDividasBD();
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
                                <div class="col-sm-10">
                                    <h1 class="h3 mb-2 text-gray-800">Devedores</h1>
                                </div>
                                <div class="col-sm-2">
                                    <a href="dividasDevedoresCadastro.php" class="btn btn-outline btn-primary btn-lg btn-block novo">Nova Dívida</a>
                                </div>
                            </div>
                            <br>

                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Lista de devedores</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Cliente</th>
                                                    <th style="width: 60px; text: center">Excluir</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php
                                                if (isset($devedores)) {

                                                    foreach ($devedores as $devedor) {
                                                        ?>


                                                        <tr>
                                                            <td><a href="devedoresDetalhes.php?idCliente=<?php echo $devedor['cliente'] ?>"><?php echo $devedor['nomeCliente'] ?></a></td>
                                                            <td style="text-align: center;margin: auto">
                                                                <a  href="#" id="linkExclusaoDevedor" data-toggle="modal" data-target="#deletaDevedorModal" idCliente="<?php echo $devedor['cliente'] ?>">
                                                                    <img src="img/delete.png" width="30%" >
                                                                </a>
                                                            </td>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <a class="scroll-to-top rounded" href="#page-top">
                <i class="fas fa-angle-up"></i>
            </a>

            <form method="post" action="" role="form">
                <!--Modal para exclusão: -->
                <div class="modal fade" id="deletaDevedorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Deletar Divida</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">Tem certeza que deseja deletar este devedor e sua(s) divida(s)?.</div>

                            <!--Campos ocultos:-->
                            <!--Tipo da tela para identificar que é exclusão: -->
                            <input type="hidden" name="tipoTela" value="exclusao">

                            <!--Campo para armazenar o id da divida:-->
                            <input type="hidden" name="idClienteModal" id="idClienteModal">

                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="submit" formaction="devedores.php" type="button" >Sim</button>
                                <a class="btn btn-primary" data-dismiss="modal" href="#">Não</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <?php include 'importaJavaScript.php'?>

    </body>

</html>