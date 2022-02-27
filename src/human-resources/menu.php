<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../connection.php';
require_once '../navbar.php';
?>
<head>
    <title>Empleados</title>
    <link rel="stylesheet" href="/css/containers.css">
    <link rel="stylesheet" href="/css/text.css">
    <link rel="stylesheet" href="/css/buttons.css">
</head>
<br>
<div class="centered-flex-container">
    <a class="blue-button" href="/human-resources/search-employees.php">Buscar empleados</a>
    <pre style="width: 1vw;"></pre>
    <a class="blue-button" href="/human-resources/generate-all-cdp.php">Generar CDP</a>
</div>