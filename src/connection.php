<?php

require 'database.php';
require 'email.php';

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
                $_ENV["DB_HOST"],
                $_ENV["DB_USERNAME"],
                $_ENV["DB_PASSWORD"],
                $_ENV["DB_DATABASE"]);
            $_SERVER["connection"]->email = new SMTPEmail(
                $_ENV["EMAIL_HOST"],
                intval($_ENV["EMAIL_PORT"]),
                $_ENV["EMAIL_USERNAME"],
                $_ENV["EMAIL_PASSWORD"]
            );
        }
    }
    return $_SERVER["connection"];
}