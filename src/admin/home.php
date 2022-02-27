<?php
require_once '../navbar.php';
?>

<head>
    <title>Admin</title>
    <link rel="stylesheet" href="/css/containers.css">
    <link rel="stylesheet" href="/css/text.css">
    <link rel="stylesheet" href="/css/buttons.css">
</head>
<div class="centered-container" style="margin-top: 10vh">
    <div class="centered-container-for-input">
        <h1 class="purple-text">Admin</h1>
        <div class="centered-flex-container">
            <a class="blue-button" href="/admin/search-users.php">Buscar usuarios</a>
            <pre style="width: 1vw;"></pre>
            <a class="blue-button" href="/admin/create-user.php">Crear usuarios</a>
        </div>
    </div>
</div>
