<?php
require_once '../ensure_login.php';
require_once '../navbar.php';
require_once 'menu-gastos.php';
?>
<body>
<div class="centered-container">
    <div class="centered-container-for-input" style="margin-top: 10vh; width: 70vw; height: 70vh;">
        <h1 class="purple-text" style="margin-top: 0.5%;">Añadir Gasto</h1>
        <?php
        if (isset($_POST['add_gasto'])) {
            $succeed = connect()->registrar_gasto(
                $_POST["gasto_valor"],
                $_POST["gasto_razon"]
            );
            if ($succeed) {
                echo "<h3 class='green-text'>Gasto agregado con exito</h3>";
            } else {
                echo "<h3 class='error-block'>No se logro agregar el gasto</h3>";
            }
        }
        ?>
        <form method="post" enctype="multipart/form-data">
            <label>
                <input required class="basic-text-input" type="text" placeholder="Razon del Gasto"
                       name="gasto_razon"
                       style="width: 75%;">
                <input required class="basic-text-input" min="0" type="number" placeholder="Valor"
                       name="gasto_valor"
                       style="width: 75%;">
            </label>
            <br>
            <button class="blue-button" type="submit" name="add_gasto" style="margin-top: 0.5%; width: 75%;">Añadir
            </button>
        </form>
    </div>
</div>
</body>
