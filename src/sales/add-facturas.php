<?php
require_once '../navbar.php';
require 'menu-factura.php';
?>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


<head>
    <title>Ventas</title>
    <link rel="stylesheet" href="/css/containers.css">
    <link rel="stylesheet" href="/css/text.css">
    <link rel="stylesheet" href="/css/buttons.css">
    <link rel="stylesheet" href="/css/select-list.css">
    <link rel="stylesheet" href="/css/table.css">
    <script src="/js/addrow-factura.js"></script>
</head>

<div class="centered-container">
    <div class="centered-container-for-input" style="margin-top: 10vh" >
        <h1 class="purple-text" style="margin-top: 0.5%;">Crear Factura</h1>
    </div>
</div>
<div class="centered-container" style="margin-top: 10vh" id="empleados">
    <?php
    $empleados = connect()->database->lista_empleados();
    echo "<select id='empleados' name='empleados' class='select-menu'>";
    echo "<option value=null selected='selected'>Seleccionar Empleado</option>";
    foreach ($empleados as $empleado) {
        echo "<option value=$empleado->id>$empleado->nombre</option>";
    }
    echo "</select>";
    ?>
</div>

<div class="centered-container" style="margin-top: 10vh" id="clientes">
    <?php
    $clientes = connect()->database->lista_clientes();
    echo "<select id='clientes' class='select-menu'>";
    echo "<option value=null selected='selected'>Seleccionar cliente</option>";
    foreach ($clientes as $cliente) {
        echo "<option value=$cliente->id>$cliente->nombre</option>";
    }
    echo "</select>";
    ?>
</div>

<div class="centered-container" style="margin-top: 10vh" id="pagos">
    <?php
    $pagos = connect()->database->lista_pagos();
    echo "<select id = 'pagos' class='select-menu'>";
    echo "<option value='' selected='selected'>Seleccionar Tipo de pago</option>";
    foreach ($pagos as $pago) {
        echo "<option value=$pago->id>$pago->pago</option>";
    }
    echo "</select>";
    ?>
</div>

<div class="centered-container" style="margin-top: 10vh">
    <input type="number" name="descuento[]" id="descuento_1" min="0" class="basic-text-input" placeholder="Descuento"
           value=null onkeypress="return solo_numeros(event)" style="width: 75%;">
</div>

<div class="centered-container" style="margin-top: 10vh">
    <table id="dataTable" class="table table-striped">
        <thead>
        <tr>
            <th>Check</th>
            <th>Producto</th>
            <th>Cantidad</th>
        </tr>
        </thead>
        <tbody id="details">
        <tr name="tabla">
            <td>
                <input type="checkbox" name="chk"/>
            </td>
            <td class="itemRow">
                <?php
                $products = connect()->database->lista_productos();
                echo "<select id = 'productos' class='select-menu'>";
                echo "<option value=null selected='selected '>Seleccionar Producto</option>";
                foreach ($products as $product) {
                    echo "<option value=$product->id>$product->nombre</option>";
                }
                echo "</select>";
                ?>
            </td>
            <td>
                <input type="number" name="quantity[]" min="1" id="quantity_1" class="basic-text-input" placeholder="Cantidad"
                       onkeypress="return solo_numeros(event)" style="width: 75%;">
            </td>
        </tr>
        </tbody>
    </table>
</div>

<div class="centered-container" style="margin-top: 10vh">
    <input class="blue-button" type="button" value="Añadir Fila" onclick="agregar_fila('dataTable');"/>
    <pre style="width: 1vw;"></pre>
    <input class="blue-button" type="button" value="Eliminar Fila" onclick="eliminar_fila('dataTable');"/>
</div>
<div class="centered-container" style="margin-top: 10vh">
    <input id="ok" class="blue-button" type="button" value="Crear Orden de Compra"/>
</div>