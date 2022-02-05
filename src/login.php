<?php

require 'connection.php';

session_start();

if (isset($_SESSION["logged-in"]) && $_SESSION["logged-in"] === true) {
    header('Location: /dashboard.php', true, 307);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = connect()->login($_POST["email"], $_POST["password"]);
    if ($user !== null) {
        $_SESSION["logged-in"] = true;
        $_SESSION['user'] = $user;
        header('Location: /dashboard.php', true, 307);
        exit;
    } else {
        ?>
        <h1 class="bad">Credenciales invalidas</h1>
        <?php
    }
}
?>

<!DOCTYPE html>
<html lang="html5">
<head>
    <title>Inicio de sesion</title>
    <link rel="stylesheet" href="css/containers.css">
    <link rel="stylesheet" href="css/text.css">
    <link rel="stylesheet" href="css/buttons.css">
</head>
<style>
    body {
        background: linear-gradient(-45deg, #5EAB5E, #5C8CFA);
        background-size: 400% 400%;
        animation: gradient 60s ease infinite;
    }

    @keyframes gradient {
        0% {
            background-position: 0 50%;
        }
        50% {
            background-position: 100% 50%;
        }
        100% {
            background-position: 0 50%;
        }
    }

    img {
        width: 50%;
        height: auto;
    }
</style>
<body>
<div class="centered-container">
    <div class="login-container">
        <h1 class="purple-text" style="margin-top: 10%;">Iniciar sesion</h1>
        <form action="login.php" method="post">
            <label>
                <input class="basic-text-input" type="email" placeholder="Correo electronico" name="email"
                       style="width: 75%;">
            </label>
            <br>
            <label>
                <input class="basic-text-input" type="password" placeholder="Contraseña" name="password"
                       style="margin-top: 5%; width: 75%;">
            </label>
            <br>
            <button class="blue-button" type="submit" style="margin-top: 5%; width: 75%;">Ingresar</button>
        </form>
        <br>
        <a href="/request-reset.php" class="blue-text" style="font-size: 15px;">dwjfvlwerhfkchjjhewchkkhwjceiegchjweb</a>
    </div>
</div>
</body>
</html>
