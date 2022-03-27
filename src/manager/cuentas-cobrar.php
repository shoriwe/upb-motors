<?php
require_once '../ensure_login.php';
require_once '../navbar.php';
require_once 'menu-informes.php';

$cuentas = connect()->database->get_cuentas_cobrar();
?>

<head>
    <link rel="stylesheet" href="/css/table.css">
</head>

<div class="centered-container" style="margin-top: 10vh">
    <h1 class="purple-text" style="margin-top: 0.5%;">Reporte Cuentas por Cobrar</h1>
</div>

<div class="centered-container" style="margin-top: 10vh">
    <table id="dataTable" class="table table-striped">
        <thead>
        <tr>
            <th>Nombre</th>
            <th>Cedula</th>
            <th>Dirección</th>
            <th>Telefono</th>
            <th>Correo</th>
            <th>Numero</th>
            <th>Fecha</th>
            <th>Valor</th>
        </tr>
        </thead>
        <tbody id="details">
        <?php
        foreach ($cuentas as $cuenta){
            echo "<tr>";
            echo "<td>$cuenta->nombre</td>";
            echo "<td>$cuenta->cedula</td>";
            echo "<td>$cuenta->direccion</td>";
            echo "<td>$cuenta->telefono</td>";
            echo "<td>$cuenta->correo</td>";
            echo "<td>$cuenta->numero</td>";
            echo "<td>$cuenta->fecha</td>";
            echo "<td>$cuenta->valor</td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
</div>


<div class="centered-container">
    <div class="centered-container-for-input" style="margin-top: 10vh; width: 70vw; height: 70vh;">
        <h2 class="purple-text" style="margin-top: 0.5%;">Información General</h2>
        <?php
        $credito = 0 + connect()->database->get_ventas_credito();
        echo "<h3 class='black-text' style='margin-top: 0.5%;'>Total cuentas por cobrar: $credito</h3>";
        ?>
    </div>
</div>