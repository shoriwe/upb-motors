<?php

require_once 'connection.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user-id"])) {
    session_destroy();
    js_redirect("/login.php");
}