<?php


    require_once '../connection.php';

    $hoy = date("Y-m-d");
    $empleado=$_POST['empleado'];
    $cliente=$_POST['cliente'];

    $succeed = connect()->database->registrar_orden(
        $empleado,
        $cliente,
        $hoy
    );
    if ($succeed) {
        echo "paso";
    } else {
        echo "no paso";
    }
?>
