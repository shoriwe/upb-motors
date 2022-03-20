<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../connection.php';
require_once '../ensure_login.php';
require 'menu.php';

$product = connect()->database->view_product($_GET["id"]);
if ($product === null) {
    js_redirect("/inventory/home.php");
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
            $dependencia = connect()->database->get_dependency_name($product->dependencia);
            echo "<h1 class='purple-text' style='margin-top: 0.5%;'>$product->nombre ($dependencia)</h1>";
            echo "<h3 class='black-text' style='margin-top: 0.5%;'>Precio $$product->precio</h3>";
            echo "<h3 class='black-text' style='margin-top: 0.5%;'>Activo: $activo</h3>";
            echo "<h3 class='black-text' style='margin-top: 0.5%;'>$product->cantidad unidades</h3>";
            echo "<p class='black-text' style='margin-top: 0.5%; padding: 1vh 1vw; box-shadow: 1px 1px 5px 0 black;'>$product->descripcion</p>";
            ?>
        </div>
        <h3 class="purple-text">Historial de precios</h3>
        <div class="list-container">
            <?php
            $old_prices = connect()->database->get_price_history($product->id);
            foreach ($old_prices as $price) {
                $price_date = date('Y-m-d H:i:s', $price->modification_date);
                echo "
<div class='list-entry'>
    <h3 class='black-text'>$price_date</h3>
    <pre style='min-width: 15vw;'></pre>
    <h3 class='black-text'>$$price->precio</h3>
</div>
";
            }
            ?>
        </div>
    </div>
</div>
</body>