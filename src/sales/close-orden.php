<?php


require_once '../connection.php';


$id = $_GET["id"];

$succeed = connect()->database->close_orden(
    $id
);
if ($succeed) {
    header("Location: /sales/buscar-ordenes.php");

    echo '<script type="text/javascript">';
    echo ' alert("Orden Cerrada")';
    echo '</script>';
} else {
    header("Location: /sales/buscar-ordenes.php");

    echo '<script type="text/javascript">';
    echo ' alert("Error")';
    echo '</script>';
}
?>