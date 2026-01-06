// **Variables**
selLinea = document.getElementById("selLinea");
selParte = document.getElementById("selParte");
selPlan = document.getElementById("selPlan")
tbody = document.getElementById('tbody');

selParte.addEventListener('change', () => {
  agregaConsulta();
});

fecha.addEventListener('change', () => {
  agregaConsulta();
});

selPlan.addEventListener('change', () => {
  agregaConsulta();
});

// **Carga de líneas**
$(document).ready(function () {
 cargaLineas();
 cargaProduct();

 selLinea.addEventListener('change', () => {
 cargaProduct()
 })
});


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
 
function cargaProduct() {
noLinea = selLinea.value.substring(0,4);
 query = "SP_MAT_REPORTE_QTYFIN '" + noLinea + "'"
 $.ajax({
 type: 'GET',
 url:'models/consultaDatos.php',
 data: {
 'query': query
 },
 success: function(data){
 var arr = JSON.parse(data)
 if (arr.length == 0) {
 selParte.innerHTML = ""
 return
 }

 // Limpia el contenido del select antes de cargar nuevos datos
 selParte.innerHTML = "";

 // Se carga el select con las lineas
 for (i=0;i<=arr.length-1;i++) {
 var opcion = document.createElement('option')
 opcion.value = arr[i].PRODUCT_NO
 opcion.text = arr[i].PRODUCT_NO
 selParte.appendChild(opcion)
 }
 }
 })
}

// **Agregar consulta**
function agregaConsulta() {
  var fecha = $("#fecha").val();
  var noLinea = selLinea.value.substring(0,4);
  var noParte = selParte.value;
  var plan = selPlan.value;

  if (!fecha || !noLinea || !noParte || !plan) {
    alert("Por favor, seleccione la fecha y la línea");
    return;
  }

  query = "SP_MAT_CONSULTA_DISTRIBUCION_POR_LINEA_NOPARTE '" + noLinea + "','" + fecha + "','" + noParte + "','" + plan + "'"

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
        var celdaMaterial = document.createElement('td');
        celdaMaterial.innerHTML = arr[i].Material_no;

        var celdaCodigo = document.createElement('td');
        celdaCodigo.innerHTML = arr[i].Location_C;

        var celdaQtyPlan = document.createElement('td');
        celdaQtyPlan.innerHTML = arr[i].PlanQty;

        var celdaPieza = document.createElement('td');
        celdaPieza.innerHTML = arr[i].BOM_Qty;

        var celdaQtyFinal = document.createElement('td');
        celdaQtyFinal.innerHTML = arr[i].ReqQty;

        fila.appendChild(celdaMaterial);
        fila.appendChild(celdaCodigo);
        fila.appendChild(celdaQtyPlan);
        fila.appendChild(celdaPieza);
        fila.appendChild(celdaQtyFinal);
        tbody.appendChild(fila);
      }
    }
  });
}

function obtenerDatos() {
  // Obtener valores de los campos
  const fecha = $("#fecha").val();
  const noLinea = selLinea.value.substring(0, 4);
  const noParte = selParte.value;
  const plan = selPlan.value;

  // Validar que no haya campos vacíos
  if (!fecha || !noLinea || !noParte || !plan) {
    alert("Por favor, seleccione todos los campos");
    return;
  }

  // Construir la consulta al procedimiento almacenado
  const query = `SP_MAT_CONSULTA_DISTRIBUCION_POR_LINEA_NOPARTE '${noLinea}', '${fecha}', '${noParte}', '${plan}'`;
  // Realizar la solicitud AJAX
  $.ajax({
    type: "GET",
    url: "models/consultaSurtido.php",
    data: {
      query,
    },
    success: function (data) {
      // Convertir la respuesta a JSON
      const datos = JSON.parse(data);

      // Validar si hay datos
      if (datos.length === 0) {
        alert("No se encontraron datos para la consulta");
        return;
      }

//delete datos

    const queryInsert = `DELETE ERP_MAT_DISTRIBUTION_PLAN WHERE Date_plan ='${fecha}' AND Line_c = '${noLinea}' AND Product_no = '${noParte}'`;

      $.ajax({
        type: "GET",
        url: "models/consultaSurtido.php",
        data: {
          'query': queryInsert,
        },
        success: function (data) {
          if (data === "OK") {
            // Mostrar mensaje de éxito
            console.log("Dato insertado correctamente");
          } else {
            // Mostrar mensaje de error
            console.log("Error al insertar dato: " + data);
          }
        },
      });




      // Recorrer los datos e insertar uno por uno
      for (const dato of datos) {
     const queryInsert = `INSERT INTO ERP_MAT_DISTRIBUTION_PLAN VALUES('${fecha}', '${noLinea}', '${noParte}', '${dato.Material_no}', '${dato.Location_C}','${dato.PlanQty}', '${dato.BOM_Qty}', '${dato.ReqQty}')`

        
        //const queryInsert = `INSERT INTO ERP_MAT_DISTRIBUTION_PLAN VALUES ('${fecha}', '${noLinea}', '${noParte}', '${dato.Material_no}', '${dato.Location_C}', ${dato.PlanQty}, ${dato.BOM_Qty}, ${dato.ReqQty})`;

        // Realizar la solicitud AJAX para la inserción
        $.ajax({
          type: "GET",
          url: "models/consultaSurtido.php",
          data: {
            'query': queryInsert,
          },
          success: function (data) {
            if (data === "OK") {
              // Mostrar mensaje de éxito
              console.log("Dato insertado correctamente");
            } else {
              // Mostrar mensaje de error
              console.log("Error al insertar dato: " + data);
            }
          },
        });
      }
      // Mostrar mensaje de finalización
      alert("Todos los datos insertados");
    },
  });
}

