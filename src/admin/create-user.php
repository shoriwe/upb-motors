<?php

require 'menu.php';

if (!connect()->database->is_admin($_SESSION["user-id"])) {
    js_redirect("/dashboard.php");
}

?>
<link rel="stylesheet" href="/css/select-menu.css">
<body>
<div class="centered-container">
    <div class="centered-container-for-input" style="margin-top: 10vh; width: 70vw; height: 70vh;">
        <h1 class="purple-text" style="margin-top: 0.5%;">Crear usuario</h1>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $succeed = connect()->database->register_user(
                intval($_POST["permission"]),
                $_POST["name"],
                $_POST["personal_id"],
                $_POST["address"],
                $_POST["phone"],
                $_POST["email"],
                $_POST["password"]
            );
            if ($succeed) {
                echo "<h3 class='green-text'>Usuario creado con exito</h3>";
            } else {
                echo "<h3 class='error-block'>No se logro crear el usuario</h3>";
            }
        }
        ?>
        <form method="post" enctype="multipart/form-data">
            <label>
                <input required class="basic-text-input" type="text" placeholder="Nombre del empleado"
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
                <br>
                <select class="select-menu" required name="permission"
                        style="width: 75%; margin-top: 1vh; margin-bottom: 1vh;">
                    <option value="1">GERENTE</option>
                    <option value="2">RECURSOS HUMANOS</option>
                    <option value="3">VENTAS</option>
                    <option value="4">INVENTARIO</option>
                    <option value="5">ADMIN</option>
                </select>
                <br>
                <input required class="basic-text-input" type="password" placeholder="Contrasena"
                       name="password"
                       style="width: 75%;">
            </label>
            <br>
            <button class="blue-button" type="submit" name="add_inventory" style="margin-top: 0.5%; width: 75%;">Añadir
            </button>
        </form>
    </div>
</div>
</body>
