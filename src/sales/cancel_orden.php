<?php
require_once '../navbar.php';
require_once '../connection.php';

$o_cliente_id = $_POST['o_cliente_id'];
$o_empleado_id = $_POST['o_empleado_id'];
$fecha = $_POST['fecha'];
$o_enabled = $_POST['o_enabled'];

$succeed = connect()->cancel_purchase(
    $o_empleado_id,
    $o_cliente_id,
    $fecha,
    $o_enabled,
);
if ($succeed) {
    echo "si";
} else {
    echo "no";
}
?>
<head>
    <title>Ventas</title>
    <link rel="stylesheet" href="/css/containers.css">
    <link rel="stylesheet" href="/css/text.css">
    <link rel="stylesheet" href="/css/buttons.css">
    <link rel="stylesheet" href="/css/select-list.css">
    <link rel="stylesheet" href="/css/table.css">
</head>
<body>
<div class="centered-container">
    <div class="centered-container-for-input" style="margin-top: 10vh; width: 90vw; height: 70vh;">
        <div class="centered-container-for-input" style="text-align: left; width: 100%;">
            <?php
            if (!connect()->is_ventas($_SESSION["user-id"])) {
                js_redirect("/dashboard.php");
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $is_enabled = false;
                if (isset($_POST["o_enabled"])) {
                    $is_enabled = true;
                }
                $succeed = connect()->cancel_purchase(
                    $_GET["o_cliente_id"],
                    $_GET["o_empleado_id"],
                    $_GET["fecha"],
                    isset($_POST["o_enabled"]),
                );
                if (!$succeed) {
                    echo "<h3 class='error-block'>No se pudo cancelar esta orden de compra</h3>";
                }
            }
            $orden = connect()->ver_orden($_GET["o_cliente_id"], $_GET["o_empleado_id"]);
            if ($orden === null) {
                js_redirect("/sales/home.php");
            }
            ?>
            <form method="post">
                <?php
                echo "<label class='black-text' for='o_cliente_id' >ID cliente</label><input class='basic-text-input' name='o_cliente_id' type='number' id='o_cliente_id' style='margin-top: 0.5%;' value='$orden->o_cliente_id' placeholder='$orden->o_cliente_id'><br>";
                echo "<label class='black-text' for='o_empleado_id' >ID empleado</label><input type='number' class='basic-text-input' name='o_empleado_id' id='o_empleado_id' style='margin-top: 0.5%;' value='$orden->o_cliente_id' placeholder='$orden->o_empleado_id'><br>";
                echo "<label class='black-text' for='fecha' >fecha</label><input type='text' class='basic-text-input' name='fecha' id='fecha' style='margin-top: 0.5%;' value='$orden->fecha' placeholder='$orden->fecha'><br>";

                if ($orden->activo) {
                    echo "<label class='black-text' for='o_enabled' >Habilitado</label><input class='basic-text-input' type='checkbox' name='o_enabled' id='o_enabled' style='margin-top: 0.5%;' checked><br>";
                } else {
                    echo "<label class='black-text' for='o_enabled' >Habilitado</label><input class='basic-text-input' type='checkbox' name='o_enabled' id='o_enabled' style='margin-top: 0.5%;'><br>";
                }
                ?>
                <br>
                <button type="submit" class="green-button">Actualizar</button>
            </form>
        </div>
        <?php
        ?>
    </div>
</div>
</body>