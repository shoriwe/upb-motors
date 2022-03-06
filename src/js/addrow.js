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
    var elemento_3 = document.createElement("input");
    elemento_3.type = "number";
    celda_3.appendChild(elemento_3);

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


        $.ajax({
            method: "POST",
            url: 'save_orden_compra.php',
            data: {
                empleado: get_empleado(),
                cliente: get_cliente(),
            },
            success: function (response) {
                console.log(response);
            }
        });

        $('#details tr').each(function () {
            var id_carro = $(this).find('select').val();
            var cantidad = $(this).find('input[type="number"]').val();
            alert(id_carro + ' ' + cantidad);
        })
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

