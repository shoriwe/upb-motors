<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../connection.php';
require_once '../ensure_login.php';
require 'menu.php';

if (!connect()->database->is_ventas($_SESSION["user-id"]) &&
    !connect()->database->is_inventario($_SESSION["user-id"])) {
    js_redirect("/dashboard.php");
}

$orden = connect()->database->view_orden($_GET["o_cliente_id"],$_GET["o_empleado_od"]);
if ($orden === null) {
    js_redirect("/inventory/home.php");
}
?>
<body>
<div class="centered-container">
    <div class="centered-container-for-input" style="margin-top: 10vh; width: 70vw; height: 70vh;">
        <div class="centered-container-for-input" style="text-align: left;">
            <?php
            if ($orden->activo) {
                $activo = "Si";
            } else {
                $activo = "No";
            }
            echo "<h1 class='purple-text' style='margin-top: 0.5%;'>Orden de compra</h1>";
            echo "<h3 class='black-text' style='margin-top: 0.5%;'>ID cliente: $orden->o_cliente_id</h3>";
            echo "<h3 class='black-text' style='margin-top: 0.5%;'>ID empleado: $orden->o_empleado_id</h3>";
            echo "<h3 class='black-text' style='margin-top: 0.5%;'>ID empleado: $orden->fecha</h3>";
            echo "<h3 class='black-text' style='margin-top: 0.5%;'>Activo: $activo</h3>";
            ?>
        </div>
        <?php
        ?>

    </div>
</div>
</body>