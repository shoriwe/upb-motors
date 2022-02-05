<?php

require 'database.php';

function &connect(): MySQL|Test
{
    static $connection = null;
    if ($connection === null) {
        if (getenv("TEST") !== false) {
            $connection = new Test();
        } else {
            $connection = new MySQL($_ENV["HOST"], $_ENV["USERNAME"], $_ENV["PASSWORD"], $_ENV["DATABASE"]);
        }
    }
    return $connection;
}