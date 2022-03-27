<?php

require_once 'connection.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION["user-id"])) {
    header('Location: /dashboard.php', true, 307);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = connect()->database->login($_POST["email"], $_POST["password"]);
    if ($user_id !== null) {
        $_SESSION['user-id'] = $user_id;
        header('Location: /dashboard.php', true, 307);
        exit;
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
            <div class="centered-container-for-input" style="margin-top: 10vh; width: 70vw; height: 50vh;">
                <h1 class="purple-text" style="margin-top: 0.5%;">Iniciar sesion</h1>
                <h3 class="error-block">Credenciales invalidas</h3>
                <form method="post">
                    <label>
                        <input required class="basic-text-input" type="email" placeholder="Correo electronico"
                               name="email"
                               style="width: 75%;">
                    </label>
                    <br>
                    <label>
                        <input required class="basic-text-input" type="password" placeholder="Contraseña"
                               name="password"
                               style="margin-top: 0.5%; width: 75%;">
                    </label>
                    <br>
                    <button class="blue-button" type="submit" style="margin-top: 0.5%; width: 75%;">Ingresar</button>
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
        <link rel="stylesheet" href="/css/containers.css">
        <link rel="stylesheet" href="/css/text.css">
        <link rel="stylesheet" href="/css/buttons.css">

    </head>
    <body>
    <div class="centered-container">
        <div class="centered-container-for-input" style="margin-top: 10vh; width: 70vw; height: 50vh;">
            <h1 class="purple-text" style="margin-top: 0.5%;">Iniciar sesion</h1>
            <form method="post">
                <label>
                    <input required class="basic-text-input" type="email" placeholder="Correo electronico" name="email"
                           style="width: 75%;">
                </label>
                <br>
                <label>
                    <input required class="basic-text-input" type="password" placeholder="Contraseña" name="password"
                           style="margin-top: 0.5%; width: 75%;">
                </label>
                <br>
                <button class="blue-button" type="submit" style="margin-top: 0.5%; width: 75%;">Ingresar</button>
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

