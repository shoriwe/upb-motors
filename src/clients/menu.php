<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../connection.php';
require_once '../navbar.php';
?>
<head>
    <title>Clientes</title>
    <link rel="stylesheet" href="/css/containers.css">
    <link rel="stylesheet" href="/css/text.css">
    <link rel="stylesheet" href="/css/buttons.css">
</head>
<br>
<div class="centered-flex-container">
    <a class="blue-button" href="#">Buscar cliente</a>
    <pre style="width: 1vw;"></pre>
    <a class="blue-button" href="#">Añadir cliente</a>
</div>