selLinea = document.getElementById("selLinea")
tbody = document.getElementById('tbody')

$(document).ready( function () {
    cargaLineas()
    cargaTablaLineas()
})

function cargaTablaLineas() {
    query = "SELECT * FROM ENS_rptStartUp_battery_lines"
    $.ajax({
        type: 'GET',
        url:'models/consultaDatos.php',
        data: {
            'query': query
        },
        success: function(data){
            var arr = JSON.parse(data)
            if (arr.length == 0) {
            } else {
                // Por cada linea en el arreglo, se crea una fila con el noLinea y nmLinea
                for (i=0;i<=arr.length-1;i++) {
                    var fila = document.createElement('tr')
                    var celdaNoLinea = document.createElement('td')
                    var celdaNmLinea = document.createElement('td')
                    var celdaEliminar = document.createElement('td')
                    celdaNoLinea.innerHTML = arr[i].Line_c
                    celdaNoLinea.style.textAlign = 'center'
                    celdaNmLinea.innerHTML = arr[i].Line_nm
                    celdaNmLinea.style.textAlign = 'center'
                    celdaEliminar.innerHTML = '<button type="button" class="btn btn-danger" onclick="eliminarLinea(\'' + arr[i].Line_c + '\')">Eliminar</button>'
                    celdaEliminar.style.textAlign = 'center'
                    fila.appendChild(celdaNoLinea)
                    fila.appendChild(celdaNmLinea)
                    fila.appendChild(celdaEliminar)
                    tbody.appendChild(fila)
                }
            }
        }
    })
}


function cargaLineas() {
    query = "SP_ENS_CARGA_LISTA_LINEAS"
    $.ajax({
        type: 'GET',
        url:'models/consultaDatos.php',
        data: {
            'query': query
        },
        success: function(data){
            var arr = JSON.parse(data)
            if (arr.length == 0) {
                alert('No hay lineas')
                return
            }

            // Se carga el select con las lineas
            for (i=0;i<=arr.length-1;i++) {
                var opcion = document.createElement('option')
                opcion.value = arr[i].Linea
                opcion.text = arr[i].Linea
                selLinea.appendChild(opcion)
            }
        }
    })
}

function agregaLinea() {
    var noLinea = selLinea.value.substring(0,4)
    var nmLinea = selLinea.value.substring(7)

    query = "INSERT INTO ENS_rptStartUp_battery_lines VALUES ('" + noLinea + "', '" + nmLinea + "')"
    $.ajax({
        type: 'GET',
        url:'models/executaQuery.php',
        data: {
            'query': query
        },
        success: function(data){
            if (data == '1') {
                // Agregamos el noLinea y nmLinea a la tabla tbody
                var row = tbody.insertRow()
                var cell1 = row.insertCell(0)
                var cell2 = row.insertCell(1)
                var celdaEliminar = row.insertCell(2)
                // Agregamos el estilo a la celda
                cell1.style.textAlign = 'center'
                cell2.style.textAlign = 'center'
                celdaEliminar.style.textAlign = 'center'
                cell1.innerHTML = noLinea
                cell2.innerHTML = nmLinea
                celdaEliminar.innerHTML = '<button type="button" class="btn btn-danger" onclick="eliminarLinea(\'' + noLinea + '\')">Eliminar</button>'
            } else {
                alert('Error linea ya agregada')
            }
        }
    })
}

function eliminarLinea(noLinea) {
    query = "DELETE FROM ENS_rptStartUp_battery_lines WHERE Line_c = '" + noLinea + "'"
    $.ajax({
        type: 'GET',
        url:'models/executaQuery.php',
        data: {
            'query': query
        },
        success: function(data){
            if (data == '1') {
                // Eliminamos la fila de la tabla
                var filas = tbody.getElementsByTagName('tr')
                for (i=0;i<=filas.length-1;i++) {
                    if (filas[i].cells[0].innerHTML == noLinea) {
                        tbody.deleteRow(i)
                        break
                    }
                }
            } else {
                alert('Error la linea ya fue eliminada')
            }
        }
    })
}