<?php

require 'menu.php';

if (!connect()->database->is_inventario($_SESSION["user-id"])) {
    js_redirect("/dashboard.php");
}

?>
<body>
<div class="centered-container">
    <div class="centered-container-for-input" style="margin-top: 10vh; width: 70vw; height: 70vh;">
        <h1 class="purple-text" style="margin-top: 0.5%;">Añadir al inventario</h1>
        <?php
        if (isset($_POST['add_inventory'])) {
            $succeed = connect()->database->add_inventory(
                $_POST["product_name"],
                $_POST["product_amount"],
                $_POST["product_description"],
                $_POST["product_price"],
                $_FILES["product_image"]["tmp_name"],
                $_POST["dependency"],
            );
            if ($succeed) {
                echo "<h3 class='green-text'>Producto agregado con exito</h3>";
            } else {
                echo "<h3 class='error-block'>No se logro agregar el producto</h3>";
            }
        }
        ?>
        <form method="post" enctype="multipart/form-data">
            <label>
                <input required class="basic-text-input" type="text" placeholder="Nombre del producto"
                       name="product_name"
                       style="width: 75%;">
                <br><label class="black-text" for="dependency">Dependencia</label>
                <select name="dependency" id="dependency">
                    <?php
                    foreach (connect()->database->list_dependencies() as $dependency) {
                        echo "<option value='$dependency->id'>$dependency->name</option>";
                    }
                    ?>
                </select><br>
                <input required class="basic-text-input" type="number" placeholder="Cantidad del producto"
                       name="product_amount"
                       style="width: 75%;">
                <input required class="basic-text-input" type="number" placeholder="Precio del producto"
                       name="product_price"
                       style="width: 75%;">
                <br>
                <label for="product_image">Imagen del producto</label>
                <input required type="file" style="margin-top: 0.5vh; margin-bottom: 0.5vh;" name="product_image"
                       id="product_image" accept="image/*">
                <label>
                    <textarea class="basic-text-input" placeholder="Descripcion del producto"
                              name="product_description"
                              style="width: 75%;"></textarea>
                </label>
            </label>
            <br>
            <button class="blue-button" type="submit" name="add_inventory" style="margin-top: 0.5%; width: 75%;">Añadir
            </button>
        </form>
    </div>
</div>
</body>
