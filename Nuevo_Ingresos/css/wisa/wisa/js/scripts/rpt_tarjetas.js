selPlanta = document.getElementById("selPlanta");

$(document).ready(function () {
  cargaPlantas();
});

function agregaConsulta() {
  var Plantas = selPlanta.value;
  query = "SP_ERP_QA_REPORTE_TERJETAS_SIN_CONFIRMAR '" + Plantas + "'";

  $.ajax({
    type: "GET",
    url: "models/consultaDatos.php",
    data: { query: query },
    success: function (data) {
      var arr = JSON.parse(data);
      tbody.innerHTML = ""; 

      for (var i = 0; i <= arr.length - 1; i++) {
        var fila = document.createElement("tr");

        var Planta = document.createElement("td");
        Planta.innerHTML = arr[i].Planta;

        var Linea = document.createElement("td");
        Linea.innerHTML = arr[i].Linea;
        Linea.style.textAlign = 'left';

        var Folio = document.createElement("td");
        let fechaRegistro = new Date(arr[i].Fecha.date);
        let FechaActual = new Date(arr[i].FechaActual.date);
        let CalcularDiferencia = FechaActual.getTime() - fechaRegistro.getTime();
        let diferenciaHoras = CalcularDiferencia / (1000 * 60 * 60);
        let backgroundColor = "";
        let color = "";

        if (diferenciaHoras < 24) {
          color = "black";
          backgroundColor = "yellow";
        } else if (diferenciaHoras < 72) {
          color = "black";
          backgroundColor = "orange";
        } else {
          color = "white";
          backgroundColor = "red";
        }

        Folio.style.backgroundColor = backgroundColor;
        Folio.style.color = color;
        Folio.innerHTML = arr[i].Folio;

        var Serial = document.createElement("td");
        Serial.innerHTML = arr[i].Serial;

        var Fecha = document.createElement("td");
        Fecha.innerHTML = arr[i].Fecha.date.substring(0, 19);

        fila.appendChild(Planta);
        fila.appendChild(Linea);
        fila.appendChild(Folio);
        fila.appendChild(Serial);
        fila.appendChild(Fecha);
        tbody.appendChild(fila);
      }
    },
  });
}

function cargaPlantas() {
  query = "SELECT distinct Planta FROM [172.30.75.22].erpdb.dbo.SIS_Reporte_correos_rechazos";
  $.ajax({
    type: "GET",
    url: "models/consultaDatos.php",
    data: { query: query },
    success: function (data) {
      var arr = JSON.parse(data);
      if (arr.length == 0) {
        alert("Error al cargar las plantas");
        return;
      }

      selPlanta.innerHTML = ""; // limpiar antes de llenar
      selPlanta.appendChild(new Option("Todas", ""));

      for (i = 0; i <= arr.length - 1; i++) {
        var opcion = document.createElement("option");
        opcion.value = arr[i].Planta.trim();
        opcion.text = arr[i].Planta.trim();
        selPlanta.appendChild(opcion);
      }
    },
  });
}
