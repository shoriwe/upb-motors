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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $products = connect()->database->search_products($_POST["product"]);
    ?>
    <div class="list-container">
        <?php
        foreach ($products as $product) {
            echo "
<div class='list-entry'>
    <h3 class='black-text'>$product->nombre</h3>
    <pre style='min-width: 15vw;'></pre>
    <h3 class='black-text'>$$product->precio</h3>
    <pre style='min-width: 15vw;'></pre>
    <h3 class='black-text'>$product->cantidad unidades</h3>
    <pre style='min-width: 10vw;'></pre>
    <a class='green-button' href='/inventory/view-product.php?id=$product->id'>Ver</a>
    <pre style='min-width: 5vw;'></pre>
    <a class='blue-button' href='/inventory/edit-product.php?id=$product->id'>Editar</a>
</div>
";
        }
        ?>
    </div>
    <?php
} else {
    ?>
    <div class="centered-container">
        <div class="centered-container-for-input" style="margin-top: 10vh; width: 70vw; height: 70vh;">
            <h1 class="purple-text" style="margin-top: 0.5%;">Buscar en el inventario</h1>
            <form method="post">
                <label>
                    <input required class="basic-text-input" type="text" placeholder="Nombre del producto"
                           name="product"
                           style="width: 75%;">
                </label>
                <br>
                <button class="blue-button" type="submit" style="margin-top: 0.5%; width: 75%;">Buscar</button>
            </form>
        </div>
    </div>
    <?php
}