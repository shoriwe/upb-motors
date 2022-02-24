<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!connect()->database->is_gerente($_SESSION["user-id"])) {
    header('Location: /dashboard.php', true, 307);
    exit;
}