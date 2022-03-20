<?php
require_once '../navbar.php';
require 'menu.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ordenes = connect()->database->buscar_orden_cliente($_POST["cliente"]);
    ?>
    <div class="list-container">

        <?php

        foreach ($ordenes as $orden) {
            if ($orden->estado == 1){
                $empleado_nombre = connect()->database->get_name_employees($orden->empleado);
                $cliente_nombre = connect()->database->get_name_clients($orden->cliente);
                $descuento_porciento = $orden->descuento*100;
                echo "
<div class='list-entry'>
    <h3 class='black-text'>Empleado: $empleado_nombre</h3>
    <pre style='min-width: 15vw;'></pre>
    <h3 class='black-text'>Cliente: $cliente_nombre</h3>
    <pre style='min-width: 15vw;'></pre>
    <h3 class='black-text'>Descuento: $descuento_porciento%</h3>
    <pre style='min-width: 10vw;'></pre>
    <a class='green-button' href='/sales/view-orden.php?id=$orden->id'>Ver</a>
    <pre style='min-width: 5vw;'></pre>
    <a class='red-button' href='/sales/close-orden.php?id=$orden->id'>Cerrar</a>
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