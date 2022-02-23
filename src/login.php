<?php

require 'connection.php';

session_start();

if (isset($_SESSION["logged-in"]) && $_SESSION["logged-in"] === true) {
    header('Location: /dashboard.php', true, 307);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = connect()->database->login($_POST["email"], $_POST["password"]);
    if ($user !== null) {
        connect()->database->log_login_succeed($_POST["email"]);
        $_SESSION["logged-in"] = true;
        $_SESSION['user'] = $user;
        header('Location: /dashboard.php', true, 307);
        exit;
    } else {
        connect()->database->log_login_failed($_POST["email"]);
        ?>
        <!DOCTYPE html>
        <html lang="html5">
        <head>
            <title>Inicio de sesion</title>
            <link rel="stylesheet" href="css/containers.css">
            <link rel="stylesheet" href="css/text.css">
            <link rel="stylesheet" href="css/buttons.css">
            <link rel="stylesheet" href="css/background.css">
        </head>
        <body>
        <div class="centered-container">
            <div class="centered-container-for-input" style="margin-top: 10vh; width: 70vw; height: 50vh;">
                <h1 class="purple-text" style="margin-top: 10%;">Iniciar sesion</h1>
                <h3 class="error-block">Credenciales invalidas</h3>
                <form action="login.php" method="post">
                    <label>
                        <input required class="basic-text-input" type="email" placeholder="Correo electronico"
                               name="email"
                               style="width: 75%;">
                    </label>
                    <br>
                    <label>
                        <input required class="basic-text-input" type="password" placeholder="Contraseña"
                               name="password"
                               style="margin-top: 5%; width: 75%;">
                    </label>
                    <br>
                    <button class="blue-button" type="submit" style="margin-top: 5%; width: 75%;">Ingresar</button>
                </form>
                <br>
                <a href="/request-reset.php" class="blue-text" style="font-size: 15px;">¿Olvidaste tu contraseña?</a>
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
        <link rel="stylesheet" href="css/containers.css">
        <link rel="stylesheet" href="css/text.css">
        <link rel="stylesheet" href="css/buttons.css">
        <link rel="stylesheet" href="css/background.css">
    </head>
    <body>
    <div class="centered-container">
        <div class="centered-container-for-input" style="margin-top: 10vh; width: 70vw; height: 50vh;">
            <h1 class="purple-text" style="margin-top: 10%;">Iniciar sesion</h1>
            <form action="login.php" method="post">
                <label>
                    <input required class="basic-text-input" type="email" placeholder="Correo electronico" name="email"
                           style="width: 75%;">
                </label>
                <br>
                <label>
                    <input required class="basic-text-input" type="password" placeholder="Contraseña" name="password"
                           style="margin-top: 5%; width: 75%;">
                </label>
                <br>
                <button class="blue-button" type="submit" style="margin-top: 5%; width: 75%;">Ingresar</button>
            </form>
            <br>
            <a href="/request-reset.php" class="blue-text" style="font-size: 15px;">¿Olvidaste tu contraseña?</a>
        </div>
    </div>
    </body>
    </html>
    <?php
}
?>

