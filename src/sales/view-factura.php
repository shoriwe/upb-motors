<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../connection.php';
require_once '../ensure_login.php';
require 'menu-factura.php';

$factura = connect()->view_factura($_GET["id"]);
$productos = connect()->details_view_factura($_GET["id"]);
$total_sin_decuento = 0;

?>
<body>
<div class="centered-container">
    <div class="centered-container-for-input" style="margin-top: 10vh; width: 70vw; height: 70vh;">
        <div class="centered-container-for-input" style="text-align: left;">
            <?php
            if ($factura->estado) {
                $activo = "Si";
            } else {
                $activo = "No";
            }
            $empleado_nombre = connect()->get_employee_name($factura->empleado);
            $cliente_nombre = connect()->get_client_name($factura->cliente);
            $pago = connect()->get_tipo_pago($productos[0]->tipo_pago_id);
            $descuento_porciento = $factura->descuento * 100;
            foreach ($productos as $producto) {
                $total_sin_decuento = $total_sin_decuento + $producto->valor_total;
            }
            $total_con_descuento = $total_sin_decuento - $total_sin_decuento * $factura->descuento;
            echo "<h1 class='purple-text' style='margin-top: 0.5%;'>Factura Numero: $factura->id</h1>";
            echo "<h3 class='black-text' style='margin-top: 0.5%;'>Empleado: $empleado_nombre</h3>";
            echo "<h3 class='black-text' style='margin-top: 0.5%;'>Cliente: $cliente_nombre</h3>";
            echo "<h3 class='black-text' style='margin-top: 0.5%;'>Sub Total: $total_sin_decuento</h3>";
            echo "<h3 class='black-text' style='margin-top: 0.5%;'>Descuento: $descuento_porciento%</h3>";
            echo "<h3 class='black-text' style='margin-top: 0.5%;'>Total: $total_con_descuento</h3>";
            echo "<h3 class='black-text' style='margin-top: 0.5%;'>Tipo de pago: $pago</h3>";
            echo "<h3 class='black-text' style='margin-top: 0.5%;'>Habilitada: $activo</h3>";
            ?>
        </div>
        <h3 class="purple-text">Lista Productos</h3>
        <div class="list-container">

            <?php

            foreach ($productos as $producto) {
                $inventario = connect()->get_product($producto->productos_id);
                echo "<div class='list-entry'>
                                <h3 class='black-text'>Nombre: $inventario->nombre</h3>
                                <pre style='min-width: 5vw;'></pre>
                                <h3 class='black-text'>Valor Unitario: $inventario->precio</h3>
                                <pre style='min-width: 5vw;'></pre>
                                <h3 class='black-text'>Cantidad: $producto->cantidad</h3>
                            </div>";
            }
            ?>
        </div>

    </div>

</div>

</body>