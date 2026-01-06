selLinea = document.getElementById("selLinea");
selVehiculo = document.getElementById("selVehiculo");
selAlias = document.getElementById("selAlias");

tbody = document.getElementById('tbody');

// **Carga de líneas**
$(document).ready(function () {
    cargaTablaLineas();
    cargaLineas();
    cargaVehiculos();
    cargaConsulta()
   });

   function cargaTablaLineas() {
    query = "SELECT * FROM ERP_MAT_DISTRIBUTION_FAMILIES"
    $.ajax({
        type: 'GET',
        url:'models/consultaDatosERP.php',
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
                    var celVehiculo = document.createElement('td')
                    var celAlias= document.createElement('td')
                    var celLinea = document.createElement('td')
                    var celdaEliminar = document.createElement('td')
                    celVehiculo.innerHTML = arr[i].Vehicle
                    celVehiculo.style.textAlign = 'center'
                    celAlias.innerHTML = arr[i].Alias
                    celAlias.style.textAlign = 'center'
                    celLinea.innerHTML = arr[i].Line_c
                    celLinea.style.textAlign = 'center'
                    celdaEliminar.innerHTML = '<button type="button" class="btn btn-danger" onclick="eliminarLinea(\'' + arr[i].Vehicle + '\',\'' + arr[i].Line_c + '\')">Eliminar</button>'
                    celdaEliminar.style.textAlign = 'center'
                    fila.appendChild(celVehiculo)
                    fila.appendChild(celAlias)
                    fila.appendChild(celLinea)
                    fila.appendChild(celdaEliminar)
                    tbody.appendChild(fila)
                }
            }
        }
    })
}

function cargaConsulta() {
    //var noLinea = selLinea.value.substring(0,4)
    tbody.innerHTML = "";
    var alias = selAlias.value
    var vehiculo = selVehiculo.value
    query =  "SELECT * FROM ERP_MAT_DISTRIBUTION_FAMILIES WHERE Vehicle = '" + vehiculo + "' and Alias = '" + alias + "'"
    $.ajax({
        type: 'GET',
        url:'models/consultaDatosERP.php',
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
                    var celVehiculo = document.createElement('td')
                    var celAlias= document.createElement('td')
                    var celLinea = document.createElement('td')
                    var celdaEliminar = document.createElement('td')
                    celVehiculo.innerHTML = arr[i].Vehicle
                    celVehiculo.style.textAlign = 'center'
                    celAlias.innerHTML = arr[i].Alias
                    celAlias.style.textAlign = 'center'
                    celLinea.innerHTML = arr[i].Line_c
                    celLinea.style.textAlign = 'center'
                    celdaEliminar.innerHTML = '<button type="button" class="btn btn-danger" onclick="eliminarLinea(\'' + arr[i].Vehicle + '\',\'' + arr[i].Line_c + '\')">Eliminar</button>'
                    celdaEliminar.style.textAlign = 'center'
                    fila.appendChild(celVehiculo)
                    fila.appendChild(celAlias)
                    fila.appendChild(celLinea)
                    fila.appendChild(celdaEliminar)
                    tbody.appendChild(fila)
                }
            }
        }
    })
}

function cargaLineas() {
    query = "SP_MAT_CARGA_LISTA_LINEAS"
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

   function cargaVehiculos() {
    query = "SELECT Vehicle FROM ERP_MAT_DISTRIBUTION_VEHICLES_MST "
    $.ajax({
    type: 'GET',
    url:'models/consultaDatosERP.php',
    data: {
    'query': query
    },
    success: function(data){
    var arr = JSON.parse(data)
    if (arr.length == 0) {
    return
    }
   
    // Se carga el select con las lineas
    for (i=0;i<=arr.length-1;i++) {
    var opcion = document.createElement('option')
    opcion.value = arr[i].Vehicle
    opcion.text = arr[i].Vehicle
    selVehiculo.appendChild(opcion)
    }
    }
    })
   }

   function agregaLinea() {
    var noLinea = selLinea.value.substring(0,4)
    var alias = selAlias.value
    var vehiculo = selVehiculo.value

    query = "INSERT INTO ERP_MAT_DISTRIBUTION_FAMILIES VALUES ('" + vehiculo + "', rtrim('" + alias + "'), '" + noLinea + "')"
    $.ajax({
        type: 'GET',
        url:'models/executaQueryERP.php',
        data: {
            'query': query
        },
        success: function(data){
            if (data == '1') {
                // Agregamos el noLinea y nmLinea a la tabla tbody
                var row = tbody.insertRow()
                var cell1 = row.insertCell(0)
                var cell2 = row.insertCell(1)
                var cell3 = row.insertCell(2)
                var celdaEliminar = row.insertCell(3)
                // Agregamos el estilo a la celda
                cell1.style.textAlign = 'center'
                cell2.style.textAlign = 'center'
                cell3.style.textAlign = 'center'
                celdaEliminar.style.textAlign = 'center'
                cell1.innerHTML = vehiculo
                cell2.innerHTML = alias
                cell3.innerHTML = noLinea
                celdaEliminar.innerHTML = '<button type="button" class="btn btn-danger" onclick="eliminarLinea(\'' + vehiculo + '\',\'' + noLinea + '\' )">Eliminar</button>'
            } else {
                alert('Error linea ya agregada')
            }
        }
    })
}

function eliminarLinea(Vehiculo, Linea) {
    query = "DELETE FROM ERP_MAT_DISTRIBUTION_FAMILIES WHERE Line_c = '" + Linea + "' AND Vehicle = '" + Vehiculo + "'"
    $.ajax({
        type: 'GET',
        url:'models/executaQueryERP.php',
        data: {
            'query': query
        },
        success: function(data){
            if (data == '1') {
                // Eliminamos la fila de la tabla
                var filas = tbody.getElementsByTagName('tr')
                for (i=0;i<=filas.length-1;i++) {
                    if (filas[i].cells[0].innerHTML == Vehiculo && filas[i].cells[2].innerHTML == Linea) {
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