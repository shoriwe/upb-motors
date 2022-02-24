<?php

require 'middleware/ensure_login.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>
<link rel="stylesheet" href="css/containers.css">
<link rel="stylesheet" href="css/text.css">
<link rel="stylesheet" href="css/buttons.css">
<div class="centered-flex-container">
    <a class="green-button" href="dashboard.php">Dashboard</a>
    <pre style="width: 1vw;"></pre>
    <a class="blue-button" href="search-inventory.php">Inventario</a>
    <pre style="width: 1vw;"></pre>
    <a class="blue-button" href="#empleados">Empleados</a>
    <pre style="width: 1vw;"></pre>
    <a class="blue-button" href="#clientes">Clientes</a>
    <pre style="width: 1vw;"></pre>
    <a class="blue-button" href="#ventas">Ventas</a>
    <pre style="width: 1vw;"></pre>
    <a class="blue-button" href="update-password.php">Actualizar contrasena</a>
    <pre style="width: 1vw;"></pre>
    <a class="red-button" href="logout.php">Cerrar sesion</a>
</div>
