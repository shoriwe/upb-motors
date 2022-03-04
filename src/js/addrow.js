function addRow(tableID) {
    var table = document.getElementById(tableID);
    var rowCount = table.rows.length;
    var row = table.insertRow(rowCount);
    var cell1 = row.insertCell(0);
    var element1 = document.createElement("input");
    element1.type = "checkbox";
    cell1.appendChild(element1);

    var cell2 = row.insertCell(1);
    var select = document.getElementById('productos');
    var select2 = document.createElement('select');
    select2.innerHTML = select2.innerHTML+select.innerHTML;

    cell2.appendChild(select2);

    var cell3 = row.insertCell(2);
    var element3 = document.createElement("input");
    element3.type = "number";
    cell3.appendChild(element3);

}



function deleteRow(tableID) {
    try {
        var table = document.getElementById(tableID);
        var rowCount = table.rows.length;
        for(var i=0; i<rowCount; i++) {
            var row = table.rows[i];
            var chkbox = row.cells[0].childNodes[0];
            if(null != chkbox && true == chkbox.checked) {
                table.deleteRow(i);
                rowCount--;
                i--;
            }
        }
    }catch(e) {
        alert(e);
    }
}

function clone(Obj) {
    let buf; // the cloned object
    if (Obj instanceof Array) {
        buf = []; // create an empty array
        var i = Obj.length;
        while (i --) {
            buf[i] = clone(Obj[i]); // recursively clone the elements
        }
        return buf;
    } else if (Obj instanceof Object) {
        buf = {}; // create an empty object
        for (const k in Obj) {
            if (obj.hasOwnProperty(k)) { // filter out another array's index
                buf[k] = clone(Obj[k]); // recursively clone the value
            }
        }
        return buf;
    } else {
        return Obj;
    }
}
