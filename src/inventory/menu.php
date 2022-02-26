<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../connection.php';
require '../navbar.php';

if (!connect()->database->is_ventas($_SESSION["user-id"]) &&
    !connect()->database->is_inventario($_SESSION["user-id"])) {
    header('Location: /dashboard.php', true, 307);
    exit;
}
?>
<head>
    <title>Inventario</title>
    <link rel="stylesheet" href="/css/containers.css">
    <link rel="stylesheet" href="/css/text.css">
    <link rel="stylesheet" href="/css/buttons.css">
</head>
<br>
<div class="centered-flex-container">
    <a class="blue-button" href="/inventory/search-inventory.php">Buscar</a>
    <pre style="width: 1vw;"></pre>
    <a class="blue-button" href="/inventory/add-inventory.php">Añadir</a>
    <pre style="width: 1vw;"></pre>
    <a class="blue-button" href="/inventory/delete-inventory.php">Eliminar</a>
</div>