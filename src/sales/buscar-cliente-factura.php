<?php
require_once '../navbar.php';
require 'menu-factura.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $facturas = connect()->buscar_factura_cliente($_POST["cliente"]);
    ?>
    <div class="list-container">

        <?php

        foreach ($facturas as $factura) {
            if ($factura->estado == 1) {
                $empleado_nombre = connect()->get_employee_name($factura->empleado);
                $cliente_nombre = connect()->get_client_name($factura->cliente);
                $descuento_porciento = $factura->descuento * 100;
                echo "
<div class='list-entry'>
    <h3 class='black-text'>Empleado: $empleado_nombre</h3>
    <pre style='min-width: 15vw;'></pre>
    <h3 class='black-text'>Cliente: $cliente_nombre</h3>
    <pre style='min-width: 15vw;'></pre>
    <h3 class='black-text'>Descuento: $descuento_porciento%</h3>
    <pre style='min-width: 10vw;'></pre>
    <a class='green-button' href='/sales/view-factura.php?id=$factura->id'>Ver</a>
    <pre style='min-width: 5vw;'></pre>
    <a class='red-button' href='/sales/close-factura.php?id=$factura->id'>Cancelar</a>
</div>
";
            }
        }
        ?>
    </div>
    <?php
} else {
    ?>
    <div class="centered-container">
        <div class="centered-container-for-input" style="margin-top: 10vh; width: 70vw; height: 70vh;">
            <h1 class="purple-text" style="margin-top: 0.5%;">Buscar por Cliente</h1>
            <form method="post">
                <label>
                    <input required class="basic-text-input" type="text" placeholder="Nombre del cliente"
                           name="cliente"
                           style="width: 75%;">
                </label>
                <br>
                <button class="blue-button" type="submit" style="margin-top: 0.5%; width: 75%;">Buscar</button>
            </form>
        </div>
    </div>
    <?php
}