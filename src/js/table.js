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
            if (null != check_box && true === check_box.checked) {
                tabla.deleteRow(i);
                count_filas--;
                i--;
            }
        }
    } catch (e) {
        alert(e);
    }
}