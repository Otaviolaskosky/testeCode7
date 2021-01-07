
<?php
include 'variaveisMongoDB.php';

include "devedores.class.php";

$classeDevedores = new devedores;

//Função para inserção, edição e exclusão do deividas:
$classeDevedores->registraFormularioDividas();

//Função para consultar clientes na api jsonplaceholder
$clientes = $classeDevedores->consultaClientesAPI();

//Caso o idDivida esteja no URL o sistema irá reconhecer como tela de edição
if (isset($_GET['idDivida'])) {
    $idDivida = $_GET['idDivida'];
    $botaoCadastrarEditar = 'Editar';
    $divida = $classeDevedores->consultaDividasBD(null, null, $idDivida);
    $selectedSelecione = null;
    $tipoTela = 'edicao';
} else {
    $botaoCadastrarEditar = 'Cadastrar';
    $selectedSelecione = 'selected';
}
?>

<!DOCTYPE html>
<html lang="en">

    <!DOCTYPE html>
    <html lang="en">

        <?php include "head.php" ?>

        <body id="page-top">

            <!-- Page Wrapper -->
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
                                        <h6 class="m-0 font-weight-bold text-primary"><?php echo isset($idDivida) ? 'Edição da Dívida' : 'Cadastro de Dívida' ?></h6>
                                    </div>

                                    <!-- Campo oculto -->
                                    <!--Caso este seja tela de edição, irá informar no formulario que está sendo editado-->
                                    <input type="hidden" name="tipoTela" value="<?php echo isset($tipoTela) ? $tipoTela : '' ?>">
                                    <input type="hidden" name="idDivida" value="<?php echo isset($idDivida) ? $idDivida : '' ?>">


                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <label>Cliente</label>
                                                <select class="form-control" name="cliente" required>

                                                    <option value="" <?php echo $selectedSelecione ?>>Selecione...</option>
                                                    <?php
                                                    foreach ($clientes as $idCliente => $nomeCliente):

                                                        $selected = null;

                                                        //Caso a o parametro $divida[$idCliente] esteja setado o sistema irá procurar o id do cliente para selecionar no option:
                                                        if (isset($divida[$idCliente])) {
                                                            if ($idCliente == $divida[$idCliente]['cliente']) {
                                                                $selected = 'selected';
                                                            }
                                                            $idClienteFixo = $idCliente;
                                                        }

                                                        echo '<option value="' . $idCliente . '" ' . $selected . '>' . $nomeCliente . '</option>';
                                                    endforeach;
                                                    ?>
                                                </select>

                                            </div>
                                            <div class="col-sm-2">
                                                <label>Valor</label>
                                                <input type="text" placeholder="VALOR" name="valor" value="<?php echo isset($idClienteFixo) ? $divida[$idClienteFixo]['valor'] : '' ?>" class="form-control valor" required>

                                            </div>

                                            <div class="col-sm-2">
                                                <label>Data</label>
                                                <input type="date" name="data" value="<?php echo isset($idClienteFixo) ? $divida[$idClienteFixo]['data'] : '' ?>" class="form-control valor" required>

                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <label>Motivo</label>
                                                <textarea class="form-control" placeholder="MOTIVO" name="motivo" required><?php echo isset($idClienteFixo) ? $divida[$idClienteFixo]['motivo'] : '' ?></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <footer class="sticky-footer bg-white">
                                        <div class="row ">
                                            <div class="col-md-12 text-center">
                                                <button id="botaoIncluirAlterarGarantia" type="submit" class="btn btn-primary "><?php echo $botaoCadastrarEditar ?></button>
                                                <a href="<?php echo isset($idClienteFixo) ? 'devedoresDetalhes.php?idCliente=' . $idClienteFixo : 'devedores.php' ?>" class="btn btn-outline btn-primary">Voltar</a>
                                            </div>
                                        </div>
                                    </footer>
                                </div>

                            </form>

                        </div>

                    </div>

                </div>

            <?php include 'importaJavaScript.php' ?>

        </body>

    </html>
