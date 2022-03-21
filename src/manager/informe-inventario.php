<?php
require_once '../ensure_login.php';
require_once '../navbar.php';
require_once 'menu-informes.php';

$inventario = connect()->database->get_informe_inventario();
?>

<head>
    <link rel="stylesheet" href="/css/table.css">
</head>

<div class="centered-container" style="margin-top: 10vh">
    <h1 class="purple-text" style="margin-top: 0.5%;">Informe de Inventario</h1>
</div>

<div class="centered-container" style="margin-top: 10vh">
    <table id="dataTable" class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Dependencia</th>
            <th>Cantidad</th>
            <th>Costo Unitario</th>
            <th>Costo Total</th>
            <th>Precio Unitario</th>
            <th>Precio Total</th>
            <th>Habilitado</th>
        </tr>
        </thead>
        <tbody id="details">
            <?php
                foreach ($inventario as $producto){
                    echo "<tr>";
                    echo "<td>$producto->id</td>";
                    echo "<td>$producto->nombre</td>";
                    echo "<td>$producto->dependencia</td>";
                    echo "<td>$producto->cantidad</td>";
                    echo "<td>$producto->costo_unitario</td>";
                    echo "<td>$producto->costo_total</td>";
                    echo "<td>$producto->precio</td>";
                    echo "<td>$producto->precio_total</td>";
                    if ($producto->habilitado){
                        $activo = "Si";
                    }
                    else{
                        $activo = "No";
                    }
                    echo "<td>$activo</td>";
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
        $productos_inventario = connect()->database->get_total_productos();
        $precio_inventario = connect()->database->get_total_precio();
        $costos = connect()->database->get_costos_ventas();
        echo "<h3 class='black-text' style='margin-top: 0.5%;'>Productos en Inventario: $productos_inventario</h3>";
        echo "<h3 class='black-text' style='margin-top: 0.5%;'>Precio del Inventario: $precio_inventario</h3>";
        echo "<h3 class='black-text' style='margin-top: 0.5%;'>Costo del Inventario: $costos</h3>";
        ?>
    </div>
</div>


