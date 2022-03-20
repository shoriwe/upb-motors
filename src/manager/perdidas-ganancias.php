<?php
require_once '../ensure_login.php';
require_once '../navbar.php';
require_once 'menu-informes.php';

$caja = connect()->database->get_ventas_caja();
$banco = connect()->database->get_ventas_bancos();
$costos = connect()->database->get_costos_ventas();
$gastos = connect()->database->get_gastos();

?>


<head>
    <link rel="stylesheet" href="/css/table.css">
</head>

<div class="centered-container" style="margin-top: 10vh">
    <h1 class="purple-text" style="margin-top: 0.5%;">Informe de Perdidas y Ganancias</h1>
</div>

<div class="centered-container" style="margin-top: 10vh">
    <table id="dataTable" class="table table-striped">
        <thead>
        <tr>
            <th>Razon</th>
            <th>Valor</th>
        </tr>
        </thead>
        <tbody id="details">
        <tr>
            <td colspan="2">
                <center><b>Ventas</b></center>
            </td>
        </tr>
        <tr>
            <td >
                <center>Ventas al Contado</center>
            </td>
            <td>
                <center>
                    <?php
                    echo "$$caja";
                    ?>
                </center>
            </td>
        </tr>
        <tr>
            <td >
                <center>Ventas por tarjeta</center>
            </td>
            <td>
                <center>
                    <?php
                    echo "$$banco";
                    ?>
                </center>
            </td>
        </tr>
        <tr>
            <td >
                <center>Total Ventas</center>
            </td>
            <td>
                <center><b>
                    <?php
                    echo "$".$banco+$caja;
                    ?></b>
                </center>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <center><b>Costos</b></center>
            </td>
        </tr>
        <tr>
            <td >
                <center>Costos de producción</center>
            </td>
            <td>
                <center>
                    <?php
                    echo "$$costos";
                    ?>
                </center>
            </td>
        </tr>
        <tr>
            <td >
                <center>Total Costos</center>
            </td>
            <td>
                <center><b>
                        <?php
                        echo "$".$costos;
                        ?></b>
                </center>
            </td>
        </tr>
        <tr><td colspan="2"><br></td></tr>
        <tr>
            <td >
                <center><b>Utilidad bruta</b></center>
            </td>
            <td>
                <center>
                    <?php
                    $utilidad_bruta = $banco+$caja-$costos;
                    echo "$".$utilidad_bruta;
                    ?>
                </center>
            </td>
        </tr>
        <tr>
            <td >
                <center><b>Gastos Generales</b></center>
            </td>
            <td>
                <center>
                    <?php
                    echo "$".$gastos;
                    ?>
                </center>
            </td>
        </tr>
        <tr>
            <td >
                <center><b>Utilidad Operativa</b></center>
            </td>
            <td>
                <center>
                    <?php
                    $utilidad_operativa = $utilidad_bruta-$gastos;
                    echo "$".$utilidad_operativa;
                    ?>
                </center>
            </td>
        </tr>
        <tr>
            <td >
                <center><b>Impuestos</b></center>
            </td>
            <td>
                <center>
                    <?php
                    $impuesto = ($banco+$caja)*0.19;
                    echo "$".$impuesto;
                    ?>
                </center>
            </td>
        </tr>
        <tr>
        <td >
            <center><b>Utilidad Neta</b></center>
        </td>
        <td>
            <center>
                <?php
                $utilidad_neta = $utilidad_operativa - $impuesto;
                echo "$".$utilidad_neta;
                ?>
            </center>
        </td>
        </tr>
        <td >
            <center><b>Dividendos</b></center>
        </td>
        <td>
            <center>
                <?php
                $dividendos = 1000000;
                echo "$".$dividendos;
                ?>
            </center>
        </td>
        </tr>
        <tr>
            <td >
                <center><b>Utilidad Retenida</b></center>
            </td>
            <td>
                <center><b>
                    <?php
                    $utilidad_retenida = $utilidad_neta - $dividendos;
                    echo "$".$utilidad_retenida;
                    ?></b>
                </center>
            </td>
        </tr>
        </tbody>
    </table>

</div>
<?php
$_SESSION['caja'] = $caja;
$_SESSION['banco'] = $banco;
$_SESSION['costos'] = $costos;
$_SESSION['gastos'] = $gastos;
$_SESSION['dividendos'] = $dividendos;
$_SESSION['utilidad_retenida'] = $utilidad_retenida;
$_SESSION['impuesto'] = $impuesto;

?>

<div class="centered-container">
    <div class="centered-container-for-input" style="margin-top: 10vh; width: 70vw; height: 70vh;">
        <form method="post" action="pie-chart-pg.php">
            <button class="blue-button" type="submit" style="margin-top: 0.5%; width: 75%;">Ver Graficas</button>
        </form>
    </div>
</div>


