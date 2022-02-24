<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user-id"])) {
    session_destroy();
    header('Location: /login.php', true, 307);
    exit;
}