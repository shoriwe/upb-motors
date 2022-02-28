<?php

require 'menu.php';

if (!connect()->database->is_ventas($_SESSION["user-id"])) {
    js_redirect("/dashboard.php");
}

?>
<link rel="stylesheet" href="/css/select-menu.css">
<body>
<div class="centered-container">
    <div class="centered-container-for-input" style="margin-top: 10vh; width: 70vw; height: 70vh;">
        <h1 class="purple-text" style="margin-top: 0.5%;">Crear cliente</h1>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $succeed = connect()->database->register_client(
                $_POST["name"],
                $_POST["personal_id"],
                $_POST["address"],
                $_POST["phone"],
                $_POST["email"]
            );
            if ($succeed) {
                echo "<h3 class='green-text'>Cliente creado con exito</h3>";
            } else {
                echo "<h3 class='error-block'>No se logro crear el cliente</h3>";
            }
        }
        ?>
        <form method="post" enctype="multipart/form-data">
            <label>
                <input required class="basic-text-input" type="text" placeholder="Nombre del cliente"
                       name="name"
                       style="width: 75%;">
                <input required class="basic-text-input" type="text" placeholder="cedula"
                       name="personal_id"
                       style="width: 75%;">
                <input required class="basic-text-input" type="text" placeholder="Direccion de residencia"
                       name="address"
                       style="width: 75%;">
                <input required class="basic-text-input" type="text" placeholder="Numero de telefono"
                       name="phone"
                       style="width: 75%;">
                <input required class="basic-text-input" type="email" placeholder="Correo"
                       name="email"
                       style="width: 75%;">
            </label>
            <br>
            <button class="blue-button" type="submit" name="add_inventory" style="margin-top: 0.5%; width: 75%;">Añadir
            </button>
        </form>
    </div>
</div>
</body>
