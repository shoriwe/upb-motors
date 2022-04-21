<?php


require_once '../connection.php';


$id = $_GET["id"];

$succeed = connect()->close_factura(
    $id
);
if ($succeed) {
    header("Location: /sales/buscar-facturas.php");

    echo '<script type="text/javascript">';
    echo ' alert("Factura Cancelada")';
    echo '</script>';
} else {
    header("Location: /sales/buscar-facturas.php");

    echo '<script type="text/javascript">';
    echo ' alert("Error)';
    echo '</script>';
}
?>