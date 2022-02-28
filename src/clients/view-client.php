<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../connection.php';
require_once '../ensure_login.php';
require 'menu.php';

if (!connect()->database->is_ventas($_SESSION["user-id"])) {
    js_redirect("/dashboard.php");
}

$client = connect()->database->view_client($_GET["id"]);
if ($client === null) {
    js_redirect("/admin/home.php");
}
?>
<body>
<div class="centered-container">
    <div class="centered-container-for-input" style="margin-top: 10vh; width: 70vw; height: 70vh;">
        <div class="centered-container-for-input" style="text-align: left;">
            <?php
            if ($client->is_enabled) {
                $activo = "Si";
            } else {
                $activo = "No";
            }
            echo "<h1 class='purple-text' style='margin-top: 0.5%;'>$client->name</h1>";
            echo "<h3 class='black-text' style='margin-top: 0.5%;'>Id: $client->id</h3>";
            echo "<h3 class='black-text' style='margin-top: 0.5%;'>Cedula: $client->personal_id</h3>";
            echo "<h3 class='black-text' style='margin-top: 0.5%;'>Activo: $activo</h3>";
            echo "<h3 class='black-text' style='margin-top: 0.5%;'>Telefono: $client->phone</h3>";
            echo "<h3 class='black-text' style='margin-top: 0.5%;'>Direccion: $client->address</h3>";
            echo "<h3 class='black-text' style='margin-top: 0.5%;'>Correo: $client->email</h3>";
            ?>
        </div>
        <?php
        ?>

    </div>
</div>
</body>