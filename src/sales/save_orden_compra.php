<?php


require_once '../connection.php';

$hoy = date("Y-m-d");
$empleado = $_POST['empleado'];
$cliente = $_POST['cliente'];
$descuento = $_POST['descuento'] / 100;

$succeed = connect()->registrar_orden(
    $empleado,
    $cliente,
    $hoy,
    $descuento
);
if ($succeed) {
    echo "paso";
} else {
    echo "no paso";
}
?>
