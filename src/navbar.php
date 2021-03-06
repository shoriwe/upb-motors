<?php
require_once 'connection.php';
require_once 'ensure_login.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<link rel="stylesheet" href="/css/containers.css">
<link rel="stylesheet" href="/css/text.css">
<link rel="stylesheet" href="/css/buttons.css">
<div class="centered-flex-container">
    <a class="green-button" href="/dashboard.php">Dashboard</a>
    <pre style="width: 1vw;"></pre>
    <?php

    if (connect()->is_inventario($_SESSION["user-id"])) {
        echo '<a class="blue-button" href="/inventory/home.php">Inventario</a><pre style="width: 1vw;"></pre>';
        echo '<a class="blue-button" href="/sales/home.php">Ventas</a><pre style="width: 1vw;"></pre>';
    } else if (connect()->is_recursos_humanos($_SESSION["user-id"])) {
        echo '<a class="blue-button" href="/human-resources/home.php">Empleados</a><pre style="width: 1vw;"></pre>';
    } else if (connect()->is_ventas($_SESSION["user-id"])) {
        echo '<a class="blue-button" href="/inventory/home.php">Inventario</a><pre style="width: 1vw;"></pre>';
        echo '<a class="blue-button" href="/sales/home.php">Ventas</a><pre style="width: 1vw;"></pre>';
        echo '<a class="blue-button" href="/clients/home.php">Clientes</a><pre style="width: 1vw;"></pre>';
    } else if (connect()->is_gerente($_SESSION["user-id"])) {
        echo '<a class="blue-button" href="/manager/home.php">Inventario</a><pre style="width: 1vw;"></pre>';
        echo '<a class="blue-button" href="/manager/informes.php">Informes</a><pre style="width: 1vw;"></pre>';
        echo '<a class="blue-button" href="/manager/gastos.php">Gastos</a><pre style="width: 1vw;"></pre>';
    } else if (connect()->is_admin($_SESSION["user-id"])) {
        echo '<a class="blue-button" href="/admin/home.php">Admin</a><pre style="width: 1vw;"></pre>';
    }
    ?>

    <a class="blue-button" href="/update-password.php">Actualizar contrasena</a>
    <pre style="width: 1vw;"></pre>
    <a class="red-button" href="/logout.php">Cerrar sesion</a>
</div>
