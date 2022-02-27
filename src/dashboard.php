<?php

require_once 'ensure_login.php';
require_once 'navbar.php';
?>
<!DOCTYPE html>
<html lang="html5">
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="/css/containers.css">
    <link rel="stylesheet" href="/css/text.css">
    <link rel="stylesheet" href="/css/buttons.css">
</head>
<body>
<div class="centered-container">
    <div class="centered-container-for-input" style="margin-top: 10vh; width: 70vw; height: 30vh;">
        <h1 class="purple-text" style="margin-top: 0.5%;">Dashboard</h1>
        <h3 class="blue-text">Solo las personas autorizadas pueden ver esta pagina</h3>
        <a class="red-button" href="/logout.php">Cerrar sesion</a>
    </div>
</div>
</body>
</html>