<?php
require 'database_v2.php';

$test = true;
$connection = null;
if ($connection === null) {
    if ($test) {
        $connection = new Test();
    } else {
        // $connection = new MySQL($_ENV["HOST"], $_ENV["USERNAME"], $_ENV["PASSWORD"],  $_ENV["DATABASE"]);
        $connection = new MySQL("localhost:3307", "root", "upb2021",  "login");
    }
}
?>