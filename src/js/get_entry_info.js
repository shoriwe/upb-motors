function get_cliente() {
    let id;
    $('#clientes').each(function () {
        id = $(this).find('#clientes').val();

    })
    return id;
}

function get_empleado() {
    let id;
    $('#empleados').each(function () {
        id = $(this).find('#empleados').val();
    })
    return id;
}

function get_pago() {
    let id;
    $('#pagos').each(function () {
        id = $(this).find('#pagos').val();
    })
    return id;
}

function get_descuento() {
    let descuento;
    $('#descuento_1').each(function () {
        descuento = $(this).find('#descuento_1').val();
    })
    return descuento;
}

function solo_numeros(evento) {
    const code = (evento.which) ? evento.which : evento.keyCode;
    if (code > 31 && (code < 48 || code > 57))
        return false;
    return true;
}
