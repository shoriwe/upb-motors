$(document).ready(function () {
    $('#ok').click(function () {
        let hacer_pedido = true;
        let descuento = document.getElementById("descuento_1").value;
        if (descuento === '') {
            alert("Debe llenar el campo descuento");
            hacer_pedido = false;
        }
        if (get_empleado() === 'null') {
            alert("Debe elegir a un empleado");
            hacer_pedido = false;
        }
        if (get_cliente() === 'null') {
            alert("Debe seleccionar a un cliente");
            hacer_pedido = false;
        }
        if (get_pago() === '') {
            alert("Debe seleccionar un metodo de pago");
            hacer_pedido = false;
        }
        $('#details tr').each(function () {
            const id_carro = $(this).find('select').val();
            const cantidad = $(this).find('input[type="number"]').val();
            if (id_carro === 'null') {
                alert("Debe seleccionar en todas las filas un carro");
                hacer_pedido = false;
            }
            if (cantidad === '') {
                alert("Debe llenar en todas las filas la cantidad");
                hacer_pedido = false;
            }
        });
        if (!hacer_pedido) {
            return
        }
        $.ajax(
            {
                method: "POST",
                url: 'save_orden_compra.php',
                data: {
                    empleado: get_empleado(),
                    cliente: get_cliente(),
                    descuento: descuento
                },
                success: function (_) {
                    let rowCount = $("#details tr").length;
                    $('#details tr').each(function () {
                            const id_carro = $(this).find('select').val();
                            const cantidad = $(this).find('input[type="number"]').val();
                            rowCount = rowCount - 1
                            $.ajax({
                                    method: "POST",
                                    url: 'save_product_orden_compra.php',
                                    data: {
                                        empleado: get_empleado(),
                                        cliente: get_cliente(),
                                        producto: id_carro,
                                        cantidad: cantidad,
                                        pagos: get_pago(),
                                    },
                                    success: function (_) {
                                        window.location.reload();
                                    }
                                }
                            );
                        }
                    )
                }
            }
        );
    })
})
