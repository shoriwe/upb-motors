<?php

require_once 'connection.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case "GET":
        ?>
        <!DOCTYPE html>
        <html lang="html5">
        <head>
            <title>Resetear contraseña</title>
            <link rel="stylesheet" href="/css/containers.css">
            <link rel="stylesheet" href="/css/text.css">
            <link rel="stylesheet" href="/css/buttons.css">

        </head>
        <body>
        <div class="centered-container">
            <div class="centered-container-for-input" style="margin-top: 10vh; width: 70vw; height: 40vh;">
                <h1 class="purple-text" style="margin-top: 10%;">Recuperar contraseña</h1>
                <form method="post">
                    <label>
                        <input class="basic-text-input" type="password" placeholder="Nueva contraseña" name="password"
                               style="width: 75%;">
                    </label>
                    <br>
                    <button class="blue-button" type="submit" style="margin-top: 5%; width: 75%;">Reset</button>
                </form>
            </div>
        </div>
        </body>
        </html>
        <?php
        break;
    case "POST":
        $code = $_GET["code"];
        $password = $_POST["password"];
        if (connect()->database->reset_password($code, $password)) {
            ?>
            <!DOCTYPE html>
            <html lang="html5">
            <head>
                <title>Resetear contraseña</title>
                <link rel="stylesheet" href="/css/containers.css">
                <link rel="stylesheet" href="/css/text.css">
                <link rel="stylesheet" href="/css/buttons.css">

            </head>
            <body>
            <div class="centered-container">
                <div class="centered-container-for-input" style="margin-top: 10vh; width: 70vw; height: 40vh;">
                    <h1 class="purple-text" style="margin-top: 10%;">Recuperar contraseña</h1>
                    <p class="black-text">Contraseña actualizada</p>
                    <a class="blue-text" href="/login.php">Ir al inicio de sesion</a>
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
                <title>Resetear contraseña</title>
                <link rel="stylesheet" href="/css/containers.css">
                <link rel="stylesheet" href="/css/text.css">
                <link rel="stylesheet" href="/css/buttons.css">

            </head>
            <body>
            <div class="centered-container">
                <div class="centered-container-for-input" style="margin-top: 10vh; width: 70vw; height: 40vh;">
                    <h1 class="purple-text" style="margin-top: 10%;">Recuperar contraseña</h1>
                    <p class="error-block">Invalid reset code</p>
                    <a class="blue-text" href="/login.php">Ir al inicio de sesion</a>
                </div>
            </div>
            </body>
            </html>
            <?php
        }
        break;
    default:
        break;
}