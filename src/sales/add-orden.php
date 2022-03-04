<?php
require_once '../navbar.php';
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
    <script src="/js/addrow.js"></script>
</head>


<div class="centered-container" style="margin-top: 10vh" id="empleados">
    <div>
        <?php
        $empleados = connect()->database->lista_empleados();
        echo "<select id='empleados' name='empleados'>";
        echo "<option value=null>Seleccionar Empleado</option>";
        foreach ($empleados as $empleado) {
            echo "<option value=$empleado->id>$empleado->nombre</option>";
        }
        echo "</select>";
        ?>
    </div>
</div>


<div class="centered-container" style="margin-top: 10vh" id="clientes">
    <div>
        <?php
        $clientes = connect()->database->lista_clientes();
        echo "<select id='clientes'>";
        echo "<option value=null>Seleccionar cliente</option>";
        foreach ($clientes as $cliente) {
            echo "<option value=$cliente->id>$cliente->nombre</option>";
        }
        echo "</select>";
        ?>
    </div>
</div>

<div class="centered-container" style="margin-top: 10vh">
            <table id="dataTable" >
                <thead>
                <tr>
                    <th>Check</th>
                    <th>Nombre Producto</th>
                    <th>Cantidad</th>
                </tr>
                </thead>
                <tbody id="details">
                <tr name="tabla">
                    <td><input type="checkbox" name="chk"/></td>
                    <td class="itemRow">
                        <?php
                        $products = connect()->database->lista_productos();
                        echo "<select id = 'productos'>";
                        echo "<option value=null>Seleccionar</option>";
                        foreach ($products as $product) {
                            echo "<option value=$product->id>$product->nombre</option>";
                        }
                        echo "</select>";
                        ?>
                    </td>
                    <td><input type="number" name="quantity[]" id="quantity_1" class="form-control quantity" autocomplete="off"></td>
                </tr>
                </tbody>
            </table>
</div>

<div class="centered-container" style="margin-top: 10vh">
        <input class="blue-button" type="button" value="Add Row" onclick="agregar_fila('dataTable');" />
    <p>&nbsp</p>
        <input class="blue-button" type="button" value="Delete Row" onclick="eliminar_fila('dataTable');" />
</div>
<div class="centered-container" style="margin-top: 10vh">
    <input id="ok" class="blue-button" type="button" value="Add Row" />
</div>


