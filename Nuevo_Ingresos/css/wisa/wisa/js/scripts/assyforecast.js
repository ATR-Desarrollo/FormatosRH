// **Variables**
selLinea = document.getElementById("selLinea");
tbody = document.getElementById('tbody');

// **Carga de líneas**
$(document).ready(function () {
  cargaLineas();
});


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

// **Agregar consulta**
function agregaConsulta() {
  var fecha = $("#fecha").val();
  var noLinea = selLinea.value.substring(0,4);

  if (!fecha || !noLinea) {
    alert("Por favor, seleccione la fecha y la línea");
    return;
  }

  query = "SP_MAT_REPORTE_MATERIAL_SURTIDO_POR_LINEA '" + fecha + "','" + noLinea + "'"

  // Construye la solicitud AJAX con la llamada al procedimiento almacenado
  $.ajax({
    type: 'GET',
    url:'models/consultaSurtido.php',
    data: {
        'query': query
    },
    success: function(data){
      var arr = JSON.parse(data);
      tbody.innerHTML = ""; // Limpia la tabla antes de rellenarla

      if (arr.length == 0) {
        alert('No se encontraron datos para la línea seleccionada');
        return;
      }

      // Crea filas de la tabla y rellénalas con datos de la respuesta
      for (var i = 0; i <= arr.length - 1; i++) {
        var fila = document.createElement('tr');

        // Suponiendo que 'Parte', 'Codigo', 'QtyPlan', 'QtySurt', 'Sobrante' son nombres de propiedades en el objeto de respuesta
        var celdaParte = document.createElement('td');
        celdaParte.innerHTML = arr[i].NoParte;

        var celdaCodigo = document.createElement('td');
        celdaCodigo.innerHTML = arr[i].Codigo;

        var celdaQtyPlan = document.createElement('td');
        celdaQtyPlan.innerHTML = arr[i].QtyPlan;

        var celdaQtySurt = document.createElement('td');
        celdaQtySurt.innerHTML = arr[i].QtySurt;

        var celdaSobrante = document.createElement('td');
        celdaSobrante.innerHTML = arr[i].Pendiente;

        fila.appendChild(celdaParte);
        fila.appendChild(celdaCodigo);
        fila.appendChild(celdaQtyPlan);
        fila.appendChild(celdaQtySurt);
        fila.appendChild(celdaSobrante);

        tbody.appendChild(fila);
      }
    }
  });
}
