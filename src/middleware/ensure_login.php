<?php

session_start();

if (!isset($_SESSION["logged-in"])) {
    session_destroy();
    header('Location: /login.php', true, 307);
    exit;
}