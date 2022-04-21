<?php

require_once 'database.php';

function js_redirect(string $target_url)
{
    echo "<script>window.location.href = '$target_url'</script>";
    exit;
}

function connect(): MySQL
{
    if (!isset($_SERVER["connection"])) {
        $_SERVER["connection"] = new MySQL(
            getenv("DB_HOST"),
            getenv("DB_USERNAME"),
            getenv("DB_PASSWORD"),
            getenv("DB_DATABASE"));

    }
    return $_SERVER["connection"];
}
