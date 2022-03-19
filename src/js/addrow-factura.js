let ciclo = 0;


function agregar_fila(id) {
    var tabla = document.getElementById(id);
    var count_filas = tabla.rows.length;
    var fila = tabla.insertRow(count_filas);
    var celda_1 = fila.insertCell(0);
    var elemento_1 = document.createElement("input");
    elemento_1.type = "checkbox";
    celda_1.appendChild(elemento_1);

    var celda_2 = fila.insertCell(1);
    var elemetos_2 = document.getElementById('productos');
    var copia_elemento_2 = document.createElement('select');
    copia_elemento_2.innerHTML = copia_elemento_2.innerHTML + elemetos_2.innerHTML;
    celda_2.appendChild(copia_elemento_2);

    var celda_3 = fila.insertCell(2);
    //var elemetos_3 = document.getElementById('quantity_1');
    var elemetos_3 = document.querySelector('#quantity_1');
    var copia_elemento_3 = elemetos_3.cloneNode(true);
    celda_3.appendChild(copia_elemento_3);

}


function eliminar_fila(id) {
    try {
        var tabla = document.getElementById(id);
        var count_filas = tabla.rows.length;
        for (var i = 0; i < count_filas; i++) {
            var fila = tabla.rows[i];
            var check_box = fila.cells[0].childNodes[0];
            if (null != check_box && true == check_box.checked) {
                tabla.deleteRow(i);
                count_filas--;
                i--;
            }
        }
    } catch (e) {
        alert(e);
    }
}

$(document).ready(function () {
    $('#ok').click(function () {

        let descuento = document.getElementById("descuento_1").value;


        $.ajax({
            method: "POST",
            url: 'save_factura.php',
            data: {
                empleado: get_empleado(),
                cliente: get_cliente(),
                descuento: descuento
            },
            success: function (response) {
                console.log(response);
            }
        });
        let rowCount = $("#details tr").length;
        $('#details tr').each(function () {
            var id_carro = $(this).find('select').val();
            var cantidad = $(this).find('input[type="number"]').val();

            rowCount = rowCount - 1
            $.ajax({
                method: "POST",
                url: 'save_product_factura.php',
                data: {
                    empleado: get_empleado(),
                    cliente: get_cliente(),
                    producto: id_carro,
                    cantidad: cantidad,
                    pagos: get_pago(),
                },
                success: function (response) {
                    console.log(response);
                }
            });

        })
        function restart(){
            document.getElementById('descuento_1').value = '';
            document.getElementById('quantity_1').value = '';
            $('#empleados option').prop('selected', function () {
                return this.defaultSelected;
            });
            $('#pagos option').prop('selected', function () {
                return this.defaultSelected;
            });
            $('#clientes option').prop('selected', function () {
                return this.defaultSelected;
            });
            $('#details option').prop('selected', function () {
                return this.defaultSelected;
            });
            window.location.reload();
        }

        setTimeout(restart, 4000);
    })
})

function get_cliente() {
    var id;
    $('#clientes').each(function () {
        var clientes = $(this).find('#clientes').val();
        id = clientes;

    })
    return id;
}

function get_empleado() {
    var id;
    $('#empleados').each(function () {
        var empleados = $(this).find('#empleados').val();
        id = empleados;
    })
    return id;
}

function get_pago() {
    var id;
    $('#pagos').each(function () {
        var pagos = $(this).find('#pagos').val();
        id = pagos;
    })
    return id;
}

function get_descuento() {
    var descuento;
    $('#descuento_1').each(function () {
        var descuentos = $(this).find('#descuento_1').val();
        descuento = descuentos;
    })
    return descuento;
}

function solo_numeros(evento) {
    var code = (evento.which) ? evento.which : evento.keyCode
    if (code > 31 && (code < 48 || code > 57))
        return false;
    return true;
}