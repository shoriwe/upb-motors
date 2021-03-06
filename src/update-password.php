<?php
require "connection.php";
require_once "ensure_login.php";
require_once 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST["new-password"] === $_POST["new-confirm-password"]) {
        $updatePasswordResult = connect()->update_password($_SESSION["user-id"], $_POST["old-password"], $_POST["new-password"]);
        if ($updatePasswordResult === null) {
            ?>
            <!DOCTYPE html>
            <html lang="html5">
            <head>
                <title>Actualizar contrasena</title>
                <link rel="stylesheet" href="/css/containers.css">
                <link rel="stylesheet" href="/css/text.css">
                <link rel="stylesheet" href="/css/buttons.css">

            </head>
            <body>
            <div class="centered-container">
                <div class="centered-container-for-input" style="margin-top: 10vh; width: 70vw; height: 70vh;">
                    <h1 class="purple-text" style="margin-top: 0.5%;">Actualizar contrasena</h1>
                    <h3 class="green-text">Contrasena actualizada con exito</h3>
                    <form method="post">
                        <label>
                            <input required class="basic-text-input" type="password" placeholder="Contrasena antigua"
                                   name="old-password"
                                   style="width: 75%;">
                        </label>
                        <br>
                        <label>
                            <input required class="basic-text-input" type="password" placeholder="Nueva contrasena"
                                   name="new-password"
                                   style="margin-top: 0.5%; width: 75%;">
                        </label>
                        <br>
                        <label>
                            <input required class="basic-text-input" type="password"
                                   placeholder="Confirmacion de nueva contrasena"
                                   name="new-confirm-password"
                                   style="margin-top: 0.5%; width: 75%;">
                        </label>
                        <br>
                        <button class="blue-button" type="submit" style="margin-top: 0.5%; width: 75%;">Actualizar
                        </button>
                    </form>
                </div>
            </div>
            </body>
            </html>
            <?php
        } else {
            ?>
            <!DOCTYPE html>
            <html lang="html5">
            <head>
                <title>Actualizar contrasena</title>
                <link rel="stylesheet" href="/css/containers.css">
                <link rel="stylesheet" href="/css/text.css">
                <link rel="stylesheet" href="/css/buttons.css">

            </head>
            <body>
            <div class="centered-container">
                <div class="centered-container-for-input" style="margin-top: 10vh; width: 70vw; height: 70vh;">
                    <h1 class="purple-text" style="margin-top: 0.5%;">Actualizar contrasena</h1>
                    <h3 class="error-block"><?php echo $updatePasswordResult; ?></h3>
                    <form method="post">
                        <label>
                            <input required class="basic-text-input" type="password" placeholder="Contrasena antigua"
                                   name="old-password"
                                   style="width: 75%;">
                        </label>
                        <br>
                        <label>
                            <input required class="basic-text-input" type="password" placeholder="Nueva contrasena"
                                   name="new-password"
                                   style="margin-top: 0.5%; width: 75%;">
                        </label>
                        <br>
                        <label>
                            <input required class="basic-text-input" type="password"
                                   placeholder="Confirmacion de nueva contrasena"
                                   name="new-confirm-password"
                                   style="margin-top: 0.5%; width: 75%;">
                        </label>
                        <br>
                        <button class="blue-button" type="submit" style="margin-top: 0.5%; width: 75%;">Actualizar
                        </button>
                    </form>
                </div>
            </div>
            </body>
            </html>
            <?php
        }
    } else {
        ?>
        <!DOCTYPE html>
        <html lang="html5">
        <head>
            <title>Inicio de sesion</title>
            <link rel="stylesheet" href="/css/containers.css">
            <link rel="stylesheet" href="/css/text.css">
            <link rel="stylesheet" href="/css/buttons.css">

        </head>
        <body>
        <div class="centered-container">
            <div class="centered-container-for-input" style="margin-top: 10vh; width: 70vw; height: 70vh;">
                <h1 class="purple-text" style="margin-top: 0.5%;">Actualizar contrasena</h1>
                <h3 class="error-block">Las confirmacion de la nueva contrasena no coincide</h3>
                <form method="post">
                    <label>
                        <input required class="basic-text-input" type="password" placeholder="Contrasena antigua"
                               name="old-password"
                               style="width: 75%;">
                    </label>
                    <br>
                    <label>
                        <input required class="basic-text-input" type="password" placeholder="Nueva contrasena"
                               name="new-password"
                               style="margin-top: 0.5%; width: 75%;">
                    </label>
                    <br>
                    <label>
                        <input required class="basic-text-input" type="password"
                               placeholder="Confirmacion de nueva contrasena"
                               name="new-confirm-password"
                               style="margin-top: 0.5%; width: 75%;">
                    </label>
                    <br>
                    <button class="blue-button" type="submit" style="margin-top: 0.5%; width: 75%;">Actualizar</button>
                </form>
            </div>
        </div>
        </body>
        </html>
        <?php
    }
} else {
    ?>
    <!DOCTYPE html>
    <html lang="html5">
    <head>
        <title>Inicio de sesion</title>
        <link rel="stylesheet" href="/css/containers.css">
        <link rel="stylesheet" href="/css/text.css">
        <link rel="stylesheet" href="/css/buttons.css">

    </head>
    <body>
    <div class="centered-container">
        <div class="centered-container-for-input" style="margin-top: 10vh; width: 70vw; height: 70vh;">
            <h1 class="purple-text" style="margin-top: 0.5%;">Actualizar contrasena</h1>
            <form method="post">
                <label>
                    <input required class="basic-text-input" type="password" placeholder="Contrasena antigua"
                           name="old-password"
                           style="width: 75%;">
                </label>
                <br>
                <label>
                    <input required class="basic-text-input" type="password" placeholder="Nueva contrasena"
                           name="new-password"
                           style="margin-top: 0.5%; width: 75%;">
                </label>
                <br>
                <label>
                    <input required class="basic-text-input" type="password"
                           placeholder="Confirmacion de nueva contrasena"
                           name="new-confirm-password"
                           style="margin-top: 0.5%; width: 75%;">
                </label>
                <br>
                <button class="blue-button" type="submit" style="margin-top: 0.5%; width: 75%;">Actualizar</button>
            </form>
        </div>
    </div>
    </body>
    </html>
    <?php
}