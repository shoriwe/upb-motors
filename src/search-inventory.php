<?php

require 'connection.php';
require 'middleware/ensure_login.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!connect()->database->is_ventas($_SESSION["user-id"]) ||
    !connect()->database->is_inventario($_SESSION["user-id"])) {
    header('Location: /dashboard.php', true, 307);
    exit;
}