<?php

require_once 'database.php';
require_once 'email.php';

function js_redirect(string $target_url)
{
    echo "<script>window.location.href = '$target_url'</script>";
    exit;
}

class Connection
{
    public iDatabase $database;
    public iEmail $email;
}

function connect(): Connection
{
    if (!isset($_SERVER["connection"])) {
        $_SERVER["connection"] = new Connection();
        if (getenv("TEST") !== false) {
            $_SERVER["connection"]->database = new TestDatabase();
            $_SERVER["connection"]->email = new TestEmail();
        } else {
            $_SERVER["connection"]->database = new MySQL(
                getenv("DB_HOST"),
                getenv("DB_USERNAME"),
                getenv("DB_PASSWORD"),
                getenv("DB_DATABASE"));
            $_SERVER["connection"]->email = new SMTPEmail(
                getenv("EMAIL_HOST"),
                intval(getenv("EMAIL_PORT")),
                getenv("EMAIL_USERNAME"),
                getenv("EMAIL_PASSWORD")
            );
        }
    }
    return $_SERVER["connection"];
}
