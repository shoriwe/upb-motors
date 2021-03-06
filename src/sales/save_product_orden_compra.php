<?php


require_once '../connection.php';

$empleado = $_POST['empleado'];
$cliente = $_POST['cliente'];
$producto = $_POST['producto'];
$cantidad = $_POST['cantidad'];
$pagos = $_POST['pagos'];

$id_orden = connect()->id_orden(
    $empleado,
    $cliente
);

$succeed = connect()->registrar_orden_producto(
    $producto,
    $cantidad,
    $id_orden,
    $pagos
);

if ($succeed) {
    echo "Orden Creada";
} else {
    $delete_detalle_orden = connect()->delete_detalles_orden($id_orden);
    $delete_orden = connect()->delete_orden($id_orden);
    echo "No se logro crear la orden";
}
?>