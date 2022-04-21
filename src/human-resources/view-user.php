<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../connection.php';
require_once '../ensure_login.php';
require 'menu.php';

if (!connect()->is_recursos_humanos($_SESSION["user-id"])) {
    js_redirect("/dashboard.php");
}

$employee = connect()->view_user($_GET["id"]);
if ($employee === null) {
    js_redirect("/human-resources/home.php");
} else if ($employee->permission === Admin) {
    js_redirect("/human-resources/home.php");
}
?>
<body>
<div class="centered-container">
    <div class="centered-container-for-input" style="margin-top: 10vh; width: 70vw; height: 70vh;">
        <div class="centered-container-for-input" style="text-align: left;">
            <?php
            if ($employee->is_enabled) {
                $activo = "Si";
            } else {
                $activo = "No";
            }
            $permission = get_permission_name($employee->permission);
            echo "<h1 class='purple-text' style='margin-top: 0.5%;'>$employee->name</h1>";
            echo "<h3 class='black-text' style='margin-top: 0.5%;'>Id: $employee->id</h3>";
            echo "<h3 class='black-text' style='margin-top: 0.5%;'>Cedula: $employee->personal_id</h3>";
            echo "<h3 class='black-text' style='margin-top: 0.5%;'>Activo: $activo</h3>";
            echo "<h3 class='black-text' style='margin-top: 0.5%;'>Telefono: $employee->phone</h3>";
            echo "<h3 class='black-text' style='margin-top: 0.5%;'>Direccion: $employee->address</h3>";
            echo "<h3 class='black-text' style='margin-top: 0.5%;'>Correo: $employee->email</h3>";
            echo "<h3 class='black-text' style='margin-top: 0.5%;'>Permiso: $permission</h3>";
            ?>
        </div>
        <?php
        ?>

    </div>
</div>
</body>