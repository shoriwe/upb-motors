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
            if (!connect()->database->is_admin($_SESSION["user-id"])) {
                js_redirect("/dashboard.php");
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $is_enabled = false;
                if (isset($_POST["enabled"])) {
                    $is_enabled = true;
                }
                $succeed = connect()->database->update_user(
                    $_GET["id"],
                    $_POST["permission"],
                    $_POST["name"],
                    $_POST["personal_id"],
                    $_POST["address"],
                    $_POST["phone"],
                    $_POST["email"],
                    $_POST["password"],
                    $is_enabled,
                );
                if (!$succeed) {
                    echo "<h3 class='error-block'>No se pudo actualizar al usuario</h3>";
                }
            }
            $employee = connect()->database->view_user($_GET["id"]);
            if ($employee === null) {
                js_redirect("/admin/home.php");
            }
            ?>
            <form method="post">
                <?php
                $permission = get_permission_name($employee->permission);
                echo "<label class='black-text' for='name' >Nombre</label><input class='basic-text-input' name='name' type='text' id='name' style='margin-top: 0.5%;' value='$employee->name' placeholder='$employee->name'><br>";
                echo "<label class='black-text' for='personal_id' >Cedula</label><input class='basic-text-input' name='personal_id' type='number' id='personal_id' style='margin-top: 0.5%;' value='$employee->personal_id' placeholder='$employee->personal_id'><br>";

                if ($employee->is_enabled) {
                    echo "<label class='black-text' for='enabled' >Habilitado</label><input class='basic-text-input' type='checkbox' name='enabled' id='enabled' style='margin-top: 0.5%;' checked><br>";
                } else {
                    echo "<label class='black-text' for='enabled' >Habilitado</label><input class='basic-text-input' type='checkbox' name='enabled' id='enabled' style='margin-top: 0.5%;'><br>";
                }

                echo "<label class='black-text' for='phone' >Telefono</label><input type='number' class='basic-text-input' name='phone' id='phone' style='margin-top: 0.5%;' value='$employee->phone' placeholder='$employee->phone'><br>";
                echo "<label class='black-text' for='address' >Direccion</label><input type='text' class='basic-text-input' name='address' id='address' style='margin-top: 0.5%;' value='$employee->address' placeholder='$employee->address'><br>";
                echo "<label class='black-text' for='email' >Correo</label><input type='email' class='basic-text-input' name='email' id='email' style='margin-top: 0.5%;' value='$employee->email' placeholder='$employee->email'><br>";
                echo "<label class='black-text' for='password' >Contrasena</label><input type='password' class='basic-text-input' name='password' id='password' style='margin-top: 0.5%;'><br>";
                $gerente_selected = ($employee->permission === Gerente) ? "selected" : "";
                $admin_selected = ($employee->permission === Admin) ? "selected" : "";
                $recursos_humanos_selected = ($employee->permission === RecursosHumanos) ? "selected" : "";
                $inventario_selected = ($employee->permission === Inventario) ? "selected" : "";
                $ventas_selected = ($employee->permission === Ventas) ? "selected" : "";

                echo '<label class="black-text" for="permission" >Permiso</label><select class="select-menu" required name="permission" id="permission" style="width: 75%; margin-top: 1vh; margin-bottom: 1vh;"><option value="1" ' . $gerente_selected . '>GERENTE</option><option value="2" ' . $recursos_humanos_selected . '>RECURSOS HUMANOS</option><option value="3" ' . $ventas_selected . '>VENTAS</option><option value="4" ' . $inventario_selected . '>INVENTARIO</option><option value="5" ' . $admin_selected . '>ADMIN</option></select><br>';
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