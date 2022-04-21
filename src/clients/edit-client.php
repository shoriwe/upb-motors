<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../connection.php';
require_once '../ensure_login.php';
require 'menu.php';

?>
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
                if (isset($_POST["enabled"])) {
                    $is_enabled = true;
                }
                $succeed = connect()->update_client(
                    $_GET["id"],
                    $_POST["name"],
                    $_POST["personal_id"],
                    $_POST["address"],
                    $_POST["phone"],
                    $_POST["email"],
                    $is_enabled,
                );
                if (!$succeed) {
                    echo "<h3 class='error-block'>No se pudo actualizar al cliente</h3>";
                }
            }
            $client = connect()->view_client($_GET["id"]);
            if ($client === null) {
                js_redirect("/admin/home.php");
            }
            ?>
            <form method="post">
                <?php
                echo "<label class='black-text' for='name' >Nombre</label><input class='basic-text-input' name='name' type='text' id='name' style='margin-top: 0.5%;' value='$client->name' placeholder='$client->name'><br>";
                echo "<label class='black-text' for='personal_id' >Cedula</label><input class='basic-text-input' name='personal_id' type='number' id='personal_id' style='margin-top: 0.5%;' value='$client->personal_id' placeholder='$client->personal_id'><br>";

                if ($client->is_enabled) {
                    echo "<label class='black-text' for='enabled' >Habilitado</label><input class='basic-text-input' type='checkbox' name='enabled' id='enabled' style='margin-top: 0.5%;' checked><br>";
                } else {
                    echo "<label class='black-text' for='enabled' >Habilitado</label><input class='basic-text-input' type='checkbox' name='enabled' id='enabled' style='margin-top: 0.5%;'><br>";
                }

                echo "<label class='black-text' for='phone' >Telefono</label><input type='number' class='basic-text-input' name='phone' id='phone' style='margin-top: 0.5%;' value='$client->phone' placeholder='$client->phone'><br>";
                echo "<label class='black-text' for='address' >Direccion</label><input type='text' class='basic-text-input' name='address' id='address' style='margin-top: 0.5%;' value='$client->address' placeholder='$client->address'><br>";
                echo "<label class='black-text' for='email' >Correo</label><input type='email' class='basic-text-input' name='email' id='email' style='margin-top: 0.5%;' value='$client->email' placeholder='$client->email'><br>";
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