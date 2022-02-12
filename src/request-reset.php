<?php

require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST["email"];
    $code = connect()->database->request_password_reset($email);
    if ($code !== null) {
        connect()->email->send_reset_code($email, $code);
    }
    ?>
    <!DOCTYPE html>
    <html lang="html5">
    <head>
        <title>Resetear contraseña</title>
        <link rel="stylesheet" href="css/containers.css">
        <link rel="stylesheet" href="css/text.css">
        <link rel="stylesheet" href="css/buttons.css">
        <link rel="stylesheet" href="css/background.css">
    </head>
    <body>
    <div class="centered-container">
        <div class="centered-container-for-input" style="margin-top: 10vh; width: 70vw; height: 30vh;">
            <h1 class="purple-text" style="margin-top: 10%;">Recuperar contraseña</h1>
            <p class="black-text">Un correo con las instrucciones de cambio de contraseña fue enviado a la cuenta</p>
            <a class="blue-text" href="login.php">Ir al inicio de sesion</a>
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
        <link rel="stylesheet" href="css/containers.css">
        <link rel="stylesheet" href="css/text.css">
        <link rel="stylesheet" href="css/buttons.css">
        <link rel="stylesheet" href="css/background.css">
    </head>
    <body>
    <div class="centered-container">
        <div class="centered-container-for-input" style="margin-top: 10vh; width: 70vw; height: 30vh;">
            <h1 class="purple-text" style="margin-top: 10%;">Recuperar contraseña</h1>
            <form action="request-reset.php" method="post">
                <label>
                    <input required class="basic-text-input" type="email" placeholder="Correo electronico" name="email"
                           style="width: 75%;">
                </label>
                <br>
                <button class="blue-button" type="submit" style="margin-top: 5%; width: 75%;">Recuperar</button>
            </form>
        </div>
    </div>
    </body>
    </html>
    <?php
}

?>