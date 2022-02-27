<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../connection.php';
require_once '../ensure_login.php';
require 'menu.php';

if (!connect()->database->is_recursos_humanos($_SESSION["user-id"])) {
    js_redirect("/dashboard.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employees = connect()->database->search_employees($_POST["name"], $_POST["personal_id"]);
    ?>
    <div class="list-container">
        <?php
        foreach ($employees as $employee) {
            if ($employee->permission === Admin) {
                continue;
            }
            $permission = get_permission_name($employee->permission);
            echo "
<div class='list-entry'>
    <h3 class='black-text'>$employee->name</h3>
    <pre style='min-width: 10vw;'></pre>
    <h3 class='black-text'>$employee->email</h3>
    <pre style='min-width: 10vw;'></pre>
    <h3 class='black-text'>$employee->personal_id</h3>
    <pre style='min-width: 10vw;'></pre>
    <h3 class='black-text'>$permission</h3>
    <pre style='min-width: 5vw;'></pre>
    <a class='green-button' href='/human-resources/view-user.php?id=$employee->id'>Ver</a>
</div>
";
        }
        ?>
    </div>
    <?php
} else {
    ?>
    <div class="centered-container">
        <div class="centered-container-for-input" style="margin-top: 10vh; width: 70vw; height: 70vh;">
            <h1 class="purple-text" style="margin-top: 0.5%;">Buscar usuarios</h1>
            <form method="post">
                <label>
                    <input class="basic-text-input" type="text" placeholder="Nombre"
                           name="name"
                           style="width: 75%;">
                    <input class="basic-text-input" type="text" placeholder="Cedula"
                           name="personal_id"
                           style="width: 75%;">
                </label>
                <br>
                <button class="blue-button" type="submit" style="margin-top: 0.5%; width: 75%;">Buscar</button>
            </form>
        </div>
    </div>
    <?php
}