<?php
require_once '../ensure_login.php';
require_once '../navbar.php';
require_once 'menu-informes.php';

$caja = 0 + connect()->database->get_ventas_caja();
$banco = 0 + connect()->database->get_ventas_bancos();
$costos = 0 + connect()->database->get_costos_ventas();
$gastos = 0 + connect()->database->get_gastos();

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
                <b>Ventas</b>
            </td>
        </tr>
        <tr>
            <td>
                Ventas al Contado
            </td>
            <td>
                <?php
                echo "$$caja";
                ?>
            </td>
        </tr>
        <tr>
            <td>
                Ventas por tarjeta
            </td>
            <td>
                <?php
                echo "$$banco";
                ?>
            </td>
        </tr>
        <tr>
            <td>
                Total Ventas
            </td>
            <td>
                <b>
                    <?php
                    echo "$" . $banco + $caja;
                    ?></b>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <b>Costos</b>
            </td>
        </tr>
        <tr>
            <td>
                Costos de producción
            </td>
            <td>
                <?php
                echo "$$costos";
                ?>
            </td>
        </tr>
        <tr>
            <td>
                Total Costos
            </td>
            <td><b>
                    <?php
                    echo "$" . $costos;
                    ?></b>
            </td>
        </tr>
        <tr>
            <td colspan="2"><br></td>
        </tr>
        <tr>
            <td>
                <b>Utilidad bruta</b>
            </td>
            <td>
                <?php
                $utilidad_bruta = $banco + $caja - $costos;
                echo "$" . $utilidad_bruta;
                ?>
            </td>
        </tr>
        <tr>
            <td>
                <b>Gastos Generales</b>
            </td>
            <td>
                <?php
                echo "$" . $gastos;
                ?>
            </td>
        </tr>
        <tr>
            <td>
                <b>Utilidad Operativa</b>
            </td>
            <td>
                <?php
                $utilidad_operativa = $utilidad_bruta - $gastos;
                echo "$" . $utilidad_operativa;
                ?>
            </td>
        </tr>
        <tr>
            <td>
                <b>Impuestos</b>
            </td>
            <td>
                <?php
                $impuesto = ($banco + $caja) * 0.19;
                echo "$" . $impuesto;
                ?>
            </td>
        </tr>
        <tr>
            <td><b>Utilidad Neta</b>
            </td>
            <td>
                <?php
                $utilidad_neta = $utilidad_operativa - $impuesto;
                echo "$" . $utilidad_neta;
                ?>
            </td>
        </tr>
        <td>
            <b>Dividendos</b>
        </td>
        <td>
            <?php
            $dividendos = 1000000;
            echo "$" . $dividendos;
            ?>
        </td>
        </tr>
        <tr>
            <td>
                <b>Utilidad Retenida</b>
            </td>
            <td><b>
                    <?php
                    $utilidad_retenida = $utilidad_neta - $dividendos;
                    echo "$" . $utilidad_retenida;
                    ?></b>
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


