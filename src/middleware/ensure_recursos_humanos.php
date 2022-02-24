<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!connect()->database->is_recursos_humanos($_SESSION["user-id"])) {
    header('Location: /dashboard.php', true, 307);
    exit;
}