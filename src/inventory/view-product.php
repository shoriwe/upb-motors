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

$product = connect()->database->view_product($_GET["id"]);
if ($product === null) {
    js_redirect("/inventory.php");
}
?>
<body>
<div class="centered-container">
    <div class="centered-container-for-input" style="margin-top: 10vh; width: 70vw; height: 70vh;">
        <?php
        if ($product->imagen === null) {
            $image_as_b64 = "";
        } else {
            $image_as_b64 = base64_encode($product->imagen);
        }
        echo "<img alt='imagen del producto' src='data:image/jpeg;base64,$image_as_b64' style='vertical-align: middle; width: 30vw; height: 30vh;'/>"
        ?>
        <div class="centered-container-for-input" style="text-align: left;">
            <?php
            if ($product->activo) {
                $activo = "Si";
            } else {
                $activo = "No";
            }
            if (strlen($product->descripcion) === 0) {
                $product->descripcion = "Producto sin descripcion";
            }
            echo "<h1 class='purple-text' style='margin-top: 0.5%;'>$product->nombre</h1>";
            echo "<h3 class='black-text' style='margin-top: 0.5%;'>Precio $$product->precio</h3>";
            echo "<h3 class='black-text' style='margin-top: 0.5%;'>Activo: $activo</h3>";
            echo "<h3 class='black-text' style='margin-top: 0.5%;'>$product->cantidad unidades</h3>";
            echo "<p class='black-text' style='margin-top: 0.5%; padding: 1vh 1vw; box-shadow: 1px 1px 5px 0 black;'>$product->descripcion</p>";
            ?>
        </div>
        <?php
        ?>

    </div>
</div>
</body>