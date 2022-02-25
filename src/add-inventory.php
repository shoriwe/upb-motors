<?php

require 'connection.php';
require 'middleware/ensure_login.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>
    <head>
        <title>Añadir</title>
        <link rel="stylesheet" href="css/containers.css">
        <link rel="stylesheet" href="css/text.css">
        <link rel="stylesheet" href="css/buttons.css">
    </head>
<?php
require 'navbar.php';

if (!connect()->database->is_ventas($_SESSION["user-id"]) &&
    !connect()->database->is_inventario($_SESSION["user-id"])) {
    header('Location: /dashboard.php', true, 307);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $products = connect()->database->add_inventory($_POST["product_id"],$_POST["product_name"],$_POST["product_amount"],$_POST["product_description"],$_POST["product_price"]);
    ?>
    <div class="centered-container">
        <div class="centered-container-for-input" style="margin-top: 10vh; width: 70vw; height: 70vh;">
            <h1 class="purple-text" style="margin-top: 10%;">Añadir al inventario</h1>
            <form method="post">
                <label>
                    <input required class="basic-text-input" type="text" placeholder="ID del producto"
                           name="product_id"
                           style="width: 75%;">
                    <input required class="basic-text-input" type="text" placeholder="Nombre del producto"
                           name="product_name"
                           style="width: 75%;">
                    <input required class="basic-text-input" type="text" placeholder="Cantidad del producto"
                           name="product_amount"
                           style="width: 75%;">
                    <input required class="basic-text-input" type="text" placeholder="Descripcion del producto"
                           name="product_description"
                           style="width: 75%;">
                    <input required class="basic-text-input" type="text" placeholder="Precio del producto"
                           name="product_price"
                           style="width: 75%;">
                </label>
                <br>
                <button class="blue-button" type="submit" style="margin-top: 5%; width: 75%;">Añadir</button>
            </form>
        </div>
    </div>
    <?php
}
