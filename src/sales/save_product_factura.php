<?php


require_once '../connection.php';

$empleado = $_POST['empleado'];
$cliente = $_POST['cliente'];
$producto = $_POST['producto'];
$cantidad = $_POST['cantidad'];
$pagos = $_POST['pagos'];

$id_factura = connect()->id_factura(
    $empleado,
    $cliente
);

$succeed = connect()->registrar_factura_producto(
    $producto,
    $cantidad,
    $id_factura,
    $pagos
);

if ($succeed) {
    echo "Factura Creada";
} else {
    $delete_detalle_factura = connect()->delete_detalles_factura($id_factura);
    $delete_factura = connect()->delete_factura($id_factura);
    echo "No se logro crear la factura";
}
?>