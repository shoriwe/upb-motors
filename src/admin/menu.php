<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../connection.php';
require_once '../navbar.php';

if (!connect()->database->is_recursos_humanos($_SESSION["user-id"])) {
    header('Location: /dashboard.php', true, 307);
    exit;
}
?>
<head>
    <title>Empleados</title>
    <link rel="stylesheet" href="/css/containers.css">
    <link rel="stylesheet" href="/css/text.css">
    <link rel="stylesheet" href="/css/buttons.css">
</head>
<br>
<div class="centered-flex-container">
    <a class="blue-button" href="#">Buscar usuarios</a>
    <pre style="width: 1vw;"></pre>
    <a class="blue-button" href="#">Crear usuarios</a>
</div>