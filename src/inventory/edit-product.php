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
            if (!connect()->is_inventario($_SESSION["user-id"])) {
                js_redirect("/dashboard.php");
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $is_enabled = false;
                if (isset($_POST["enabled"])) {
                    $is_enabled = true;
                }

                $succeed = connect()->update_product(
                    $_GET["id"],
                    $_POST["amount"],
                    $_POST["name"],
                    $_POST["description"],
                    $_POST["price"],
                    isset($_POST["enabled"]),
                    $_POST["dependency"]
                );
                if (!$succeed) {
                    echo "<h3 class='error-block'>No se pudo actualizar al usuario</h3>";
                }
            }
            $product = connect()->view_product($_GET["id"]);
            if ($product === null) {
                js_redirect("/inventory/home.php");
            }
            ?>
            <form method="post">
                <?php
                echo "<label class='black-text' for='name' >Nombre</label><input class='basic-text-input' name='name' type='text' id='name' style='margin-top: 0.5%;' value='$product->nombre' placeholder='$product->nombre'><br>";
                ?>

                <label class="black-text" for="dependency">Dependencia</label>
                <select name="dependency" id="dependency">
                    <?php
                    foreach (connect()->list_dependencies() as $dependency) {
                        if ($dependency->id == $product->dependencia) {
                            echo "<option value='$dependency->id' selected='selected'>$dependency->name</option>";
                        } else {
                            echo "<option value='$dependency->id'>$dependency->name</option>";
                        }
                    }
                    ?>
                </select><br>
                <?php
                echo "<label class='black-text' for='amount' >Cantidad</label><input type='number' class='basic-text-input' name='amount' id='amount' style='margin-top: 0.5%;' value='$product->cantidad' placeholder='$product->cantidad'><br>";
                echo "<label class='black-text' for='price' >Precio</label><input type='number' class='basic-text-input' name='price' id='price' style='margin-top: 0.5%;' value='$product->precio' placeholder='$product->precio'><br>";
                if ($product->activo) {
                    echo "<label class='black-text' for='enabled' >Habilitado</label><input class='basic-text-input' type='checkbox' name='enabled' id='enabled' style='margin-top: 0.5%;' checked><br>";
                } else {
                    echo "<label class='black-text' for='enabled' >Habilitado</label><input class='basic-text-input' type='checkbox' name='enabled' id='enabled' style='margin-top: 0.5%;'><br>";
                }
                echo "<textarea class='basic-text-input' name='description' id='description' style='margin-top: 0.5%;' placeholder='$product->descripcion'>$product->descripcion</textarea><br>";

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