<?php
require '../navbar.php';
?>

<head>
    <title>Inventario</title>
    <link rel="stylesheet" href="/css/containers.css">
    <link rel="stylesheet" href="/css/text.css">
    <link rel="stylesheet" href="/css/buttons.css">
</head>
<div class="centered-container" style="margin-top: 10vh">
    <div class="centered-container-for-input">
        <h1 class="purple-text">Inventario</h1>
        <div class="centered-flex-container">
            <a class="blue-button" href="/inventory/search-inventory.php">Buscar</a>
            <pre style="width: 1vw;"></pre>
            <a class="blue-button" href="/inventory/add-inventory.php">Añadir</a>
            <pre style="width: 1vw;"></pre>
            <a class="blue-button" href="/inventory/add-inventory.php">Eliminar</a>
        </div>
    </div>
</div>
