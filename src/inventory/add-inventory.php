<?php

require 'menu.php';

if (!connect()->database->is_ventas($_SESSION["user-id"]) &&
    !connect()->database->is_inventario($_SESSION["user-id"])) {
    header('Location: /dashboard.php', true, 307);
    exit;
}

if (isset($_POST['add_inventory'])) {
    $products = connect()->database->add_inventory($_POST["product_name"], $_POST["product_amount"], $_POST["product_description"], $_POST["product_price"]);
}
?>
<body>
<div class="centered-container">
    <div class="centered-container-for-input" style="margin-top: 10vh; width: 70vw; height: 70vh;">
        <h1 class="purple-text" style="margin-top: 10%;">Añadir al inventario</h1>
        <form method="post">
            <label>
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
            <button class="blue-button" type="submit" name="add_inventory" style="margin-top: 5%; width: 75%;">Añadir
            </button>
        </form>
    </div>
</div>
</body>
}
