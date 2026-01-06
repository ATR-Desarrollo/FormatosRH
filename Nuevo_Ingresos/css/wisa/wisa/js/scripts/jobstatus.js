var arr = [];

$(document).ready(function () {
    cargaPlantas();
});

function agregaConsulta() {
    var planta = selPlanta.value;
    // Buscamos la planta del select en arr
    var plantaConexion = arr.find(function (element) {
        return element.Planta == planta;
    })["Conexion"].split(";");

    var srv = plantaConexion[0].split("=")[1].trim();
    var db = plantaConexion[1].split("=")[1].trim();
    var uid = plantaConexion[3].split("=")[1].trim();
    var psw = plantaConexion[4].split("=")[1].trim();

    query = "SP_ATRIS_JOBS_HISTORY";
    $.ajax({
        type: "GET",
        url: "models/consultaPlanta.php",
        data: {
        query: query,
        srv: srv,
        db: db,
        uid: uid,
        psw: psw
        },
        success: function (data) {
        var arrQuery = JSON.parse(data);
        if (arrQuery.length == 0) {
        } else {
            // Por cada linea en el arreglo, se crea una fila con el noLinea y nmLinea
            for (i = 0; i<= arrQuery.length - 1; i++) {
            var fila = document.createElement("tr");
            var celJob = document.createElement("td");
            var celStart = document.createElement("td");
            var celStop = document.createElement("td");
            var celdaMessage = document.createElement("td");
            var celdaRun = document.createElement("td");
            celJob.innerHTML = arrQuery[i].job_name;
            celJob.style.textAlign = "center";
            celStart.innerHTML = arrQuery[i].start_execution_date.date.substring(
                0,
                19
            );
            celStart.style.textAlign = "center";
            celStop.innerHTML = arrQuery[i].stop_execution_date.date.substring(0, 19);
            celStop.style.textAlign = "center";
            celdaMessage.innerHTML = arrQuery[i].message;
            celdaMessage.style.textAlign = "center";
            celdaRun.innerHTML = arrQuery[i].run_status;
            celdaRun.style.textAlign = "center";
            fila.appendChild(celJob);
            fila.appendChild(celStart);
            fila.appendChild(celStop);
            fila.appendChild(celdaMessage);
            fila.appendChild(celdaRun);
            tbody.appendChild(fila);
            }
        }
        },
    });
}

    function cargaPlantas() {
    query = "SELECT DISTINCT Planta, Conexion FROM ADM_conexiones WHERE BD = 'ERP'";
    $.ajax({
        type: "GET",
        url: "models/consultaDatosERPDB.php",
        data: {
        query: query,
        },
        success: function (data) {
        arr = JSON.parse(data);
        if (arr.length == 0) {
            alert("No hay plantas");
            return;
        }

        // Se carga el select con las lineas
        for (i = 0; i <= arr.length - 1; i++) {
            var opcion = new Option(arr[i].Planta.trim());
            selPlanta.appendChild(opcion);
        }

        },
    });
    }
