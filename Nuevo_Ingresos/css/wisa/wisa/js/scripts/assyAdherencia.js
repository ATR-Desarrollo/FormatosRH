// **Agregar consulta**
function agregaConsulta() {
  query = "SP_CONSULTA_DISTRIBUCION_REPORTE_MATERIAL_ADHERENCIA";

  // Construye la solicitud AJAX con la llamada al procedimiento almacenado
  $.ajax({
    type: "GET",
    url: "models/consultaSurtido.php",
    data: {
      query: query,
    },
    success: function (data) {
      var arr = JSON.parse(data);
      tbody.innerHTML = ""; // Limpia la tabla antes de rellenarla

      // Crea filas de la tabla y rellénalas con datos de la respuesta
      for (var i = 0; i <= arr.length - 1; i++) {
        var fila = document.createElement("tr");

        // Suponiendo que 'Parte', 'Codigo', 'QtyPlan', 'QtySurt', 'Sobrante' son nombres de propiedades en el objeto de respuesta
        var celdaMaterial = document.createElement("td");
        celdaMaterial.innerHTML = arr[i].MaterialNo;

        var celdaCodigo = document.createElement("td");
        celdaCodigo.innerHTML = arr[i].Location_C;

        var celdaQty = document.createElement("td");
        celdaQty.innerHTML = arr[i].Qty;

        var celdaFecha = document.createElement("td");
        celdaFecha.innerHTML = arr[i].FechaReg

        fila.appendChild(celdaMaterial);
        fila.appendChild(celdaCodigo);
        fila.appendChild(celdaQty);
        fila.appendChild(celdaFecha);
        tbody.appendChild(fila);
      }
    },
  });
}
