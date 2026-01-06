var extensionArchivoGlobal = ""
var tipoTrabajoGlobal = "Nuevo"

document.addEventListener("DOMContentLoaded",function(){
    var text_max = 500
    $('#count_message').html('0 / ' + text_max )
    $('#inputDescripcion').keyup(function() {
        let text_length = $('#inputDescripcion').val().length
        
        $('#count_message').html(text_length + ' / ' + text_max)
    });

    document.getElementById("inputCorreo").style.display = "none"

    searchDepartments()
})

document.addEventListener("change", function(event){
    if(event.target.name === "tipo2" && event.target.value === "Nuevo" && event.target.checked){
        tipoTrabajoGlobal = "Nuevo"

        const inputIds = [
            "inputReloj",
            "inputNombreReloj",
            "inputCorreo",
            "selectDpto",
            "inputNombreEncargado",
            "inputNombreSistema",
            "inputNombreModRep",
            "inputNumeroUsuarios",
            "inputDepartsInvol",
            "inputDescripcion",
        ]

        const inputIdsNuevo = [
            "inputEquipo",
            "inputSoftware",
            "inputSalario",
            "inputAhorro",
            "inputAreaDepart",
            "inputAntesDir",
            "inputAntesInd",
            "inputAntesAdm",
            "inputAntesTotal",
            "inputDespDir",
            "inputDespInd",
            "inputDespAdm",
            "inputDespTotal",
            "inputDifeDir",
            "inputDifeInd",
            "inputDifeAdm",
            "inputDifeTotal"
        ]

        inputIds.forEach(id => {
            document.getElementById(id).style.border = ""
        })

        inputIdsNuevo.forEach(id => {
            document.getElementById(id).style.border = ""
        })

        document.getElementById("inputEquipo").disabled   = false
        document.getElementById("inputSoftware").disabled = false
        document.getElementById("inputSalario").disabled  = false
        document.getElementById("inputAhorro").disabled   = false
        
        document.getElementById("inputAreaDepart").disabled = false

        document.getElementById("inputAntesDir").disabled   = false
        document.getElementById("inputAntesInd").disabled   = false
        document.getElementById("inputAntesAdm").disabled   = false
        document.getElementById("inputAntesTotal").disabled = false

        document.getElementById("inputDespDir").disabled   = false
        document.getElementById("inputDespInd").disabled   = false
        document.getElementById("inputDespAdm").disabled   = false
        document.getElementById("inputDespTotal").disabled = false

        document.getElementById("inputDifeDir").disabled   = false
        document.getElementById("inputDifeInd").disabled   = false
        document.getElementById("inputDifeAdm").disabled   = false
        document.getElementById("inputDifeTotal").disabled = false
    }

    if(event.target.name === "tipo2" && event.target.value === "Cambio" && event.target.checked){
        tipoTrabajoGlobal = "Cambio"

        const inputIds = [
            "inputReloj",
            "inputNombreReloj",
            "inputCorreo",
            "selectDpto",
            "inputNombreEncargado",
            "inputNombreSistema",
            "inputNombreModRep",
            "inputNumeroUsuarios",
            "inputDepartsInvol",
            "inputDescripcion",
        ]

        const inputIdsNuevo = [
            "inputEquipo",
            "inputSoftware",
            "inputSalario",
            "inputAhorro",
            "inputAreaDepart",
            "inputAntesDir",
            "inputAntesInd",
            "inputAntesAdm",
            "inputAntesTotal",
            "inputDespDir",
            "inputDespInd",
            "inputDespAdm",
            "inputDespTotal",
            "inputDifeDir",
            "inputDifeInd",
            "inputDifeAdm",
            "inputDifeTotal"
        ]

        inputIds.forEach(id => {
            document.getElementById(id).style.border = ""
        })

        inputIdsNuevo.forEach(id => {
            document.getElementById(id).style.border = ""
        })

        document.getElementById("inputEquipo").disabled   = true
        document.getElementById("inputSoftware").disabled = true
        document.getElementById("inputSalario").disabled  = true
        document.getElementById("inputAhorro").disabled   = true

        document.getElementById("inputAreaDepart").disabled = true

        document.getElementById("inputAntesDir").disabled   = true
        document.getElementById("inputAntesInd").disabled   = true
        document.getElementById("inputAntesAdm").disabled   = true
        document.getElementById("inputAntesTotal").disabled = true

        document.getElementById("inputDespDir").disabled   = true
        document.getElementById("inputDespInd").disabled   = true
        document.getElementById("inputDespAdm").disabled   = true
        document.getElementById("inputDespTotal").disabled = true

        document.getElementById("inputDifeDir").disabled   = true
        document.getElementById("inputDifeInd").disabled   = true
        document.getElementById("inputDifeAdm").disabled   = true
        document.getElementById("inputDifeTotal").disabled = true

        //
        document.getElementById("inputEquipo").value   = ""
        document.getElementById("inputSoftware").value = ""
        document.getElementById("inputSalario").value  = ""
        document.getElementById("inputAhorro").value   = ""
        document.getElementById("inputROI").value      = ""

        document.getElementById("inputAreaDepart").value = ""

        document.getElementById("inputAntesDir").value   = ""
        document.getElementById("inputAntesInd").value   = ""
        document.getElementById("inputAntesAdm").value   = ""
        document.getElementById("inputAntesTotal").value = ""

        document.getElementById("inputDespDir").value   = ""
        document.getElementById("inputDespInd").value   = ""
        document.getElementById("inputDespAdm").value   = ""
        document.getElementById("inputDespTotal").value = ""

        document.getElementById("inputDifeDir").value   = ""
        document.getElementById("inputDifeInd").value   = ""
        document.getElementById("inputDifeAdm").value   = ""
        document.getElementById("inputDifeTotal").value = ""        
    }

    if(event.target.name === "ROI"){
        var equipo   = document.getElementById("inputEquipo").value
        var software = document.getElementById("inputSoftware").value
        var salario  = document.getElementById("inputSalario").value
        var ahorro   = document.getElementById("inputAhorro").value

        var equipoInt   = parseInt(equipo,   10)
        var softwareInt = parseInt(software, 10)
        var salarioInt  = parseInt(salario,  10)
        var ahorroInt   = parseInt(ahorro,   10)

        if(equipo != "" && software != "" && salario != "" && ahorro != ""){
            let roi = (100 * (ahorroInt/(equipoInt + softwareInt + salarioInt))).toFixed(2)
            if( roi == "NaN" || roi == "Infinity"){ roi = 0 }
            document.getElementById("inputROI").value = roi + "%"
        }

    }

    if(event.target.name === "HEADCOUNT"){
        if(tipoTrabajoGlobal == "Nuevo"){
            //-------------------------------------------------------
            let AD = document.getElementById("inputAntesDir").value
            let DD = document.getElementById("inputDespDir").value

            document.getElementById("inputDifeDir").value = AD - DD
            //-------------------------------------------------------
            let AI = document.getElementById("inputAntesInd").value
            let DI = document.getElementById("inputDespInd").value

            document.getElementById("inputDifeInd").value = AI - DI
            //-------------------------------------------------------
            let AA = document.getElementById("inputAntesAdm").value
            let DA = document.getElementById("inputDespAdm").value
            
            document.getElementById("inputDifeAdm").value = AA - DA
            //-------------------------------------------------------
            let AT = document.getElementById("inputAntesTotal").value
            let DT = document.getElementById("inputDespTotal").value
            
            document.getElementById("inputDifeTotal").value = AT - DT
        }
    }
})

document.getElementById("inputReloj").addEventListener("keydown", function(event){
    if(event.key === "Enter") { searchName() }
})

document.getElementById("selectDpto").addEventListener("change", function(event){
    var departamento = document.getElementById("selectDpto").value
    query = "select nombreEncargado, correoEncargado from SCDSIS_Encargados_departamentos where departamento = '"+departamento+"'"
    $.ajax({
        type: "GET",
        url: "models/consultaDatos.php",
        data: {
            query: query,
        },
        success: function (data) {
            var arr = JSON.parse(data)
            if(arr.length > 0){
                document.getElementById("inputNombreEncargado").value = arr[0].nombreEncargado
                document.getElementById("inputCorreo").value = arr[0].correoEncargado
            }else{
                Swal.fire({
                    icon: "warning",
                    title: "¡Alerta!",
                    text: "No se encontró el departamento.",
                })
                document.getElementById("inputNombreEncargado").value = ""
                document.getElementById("inputCorreo").value = ""
            }
        }
    })
})

document.getElementById("inputReloj").addEventListener("change", function(event){
    document.getElementById("inputNombreReloj").value = ""
})

function searchName(){
    var reloj = document.getElementById("inputReloj").value
    if(reloj == ""){
        Swal.fire({
            icon: "warning",
            title: "¡Alerta!",
            text: "Escriba el número de empleado.",
        })
        return
    }
    query = "SELECT PRETTYNAME FROM dbo.erp_aaids_colabora where cb_codigo = '"+ reloj.trim() +"'";
    $.ajax({
        type: "GET",
        url: "models/consultaColaborador.php",
        data: {
            query: query,
        },
        success: function (data) {
            var arr = JSON.parse(data)
            if(arr.length > 0){
                document.getElementById("inputNombreReloj").value = arr[0].PRETTYNAME
            }else{
                Swal.fire({
                    icon: "warning",
                    title: "¡Alerta!",
                    text: "El número de empleado no fue encontrado.",
                })
                document.getElementById("inputNombreReloj").value = ""
            }
        }
    })
}

function searchDepartments(){
    var select = document.getElementById("selectDpto")
    select.innerHTML = '<option value="">Seleccione un Departamento</option>'
    
    query = "select distinct(departamento) from SCDSIS_Encargados_departamentos"
    $.ajax({
        type: "GET",
        url: "models/consultaDatos.php",
        data: {
            query: query,
        },
        success: function (data) {
            var arr = JSON.parse(data)
            if(arr.length > 0){
                for(const i of arr){
                    var option = document.createElement("option")
                    option.value = option.text = i.departamento
                    select.appendChild(option)
                }
            }else{
                Swal.fire({
                    icon: "error",
                    title: "¡Error!",
                    text: "Departamentos no encontrados.",
                })
            }
        }
    })
}

function loadFile(folio){
    const allowedExtensions = ['pdf','xls','xlsx','doc','docx','ppt','pptx','jpg','png','jpeg']
    
    const fileInput = document.getElementById("fileInput")
    const file = fileInput.files[0]

    if(file) {
        const maxSizeInBytes = 2 * 1024 * 1024;
        if (file.size > maxSizeInBytes) {
            Swal.fire({
                icon: "error",
                title: "¡Error!",
                text: "El archivo excede el tamaño máximo permitido de 2 MB.",
            });
            return;
        }
        const fileExtension = file.name.split('.').pop();

        if(!allowedExtensions.includes(fileExtension)){
            Swal.fire({
                icon: "warning",
                title: "¡Alerta!",
                text: "El tipo de archivo seleccionado ('"+fileExtension+"') no es permitido."
                    +"Utilice alguno de los siguientes: pdf, xls, xlsx, doc, docx, ppt, pptx, jpg, png o jpeg",
            });
            return;
        }

        extensionArchivoGlobal = fileExtension
        const newFileName = `${folio}.${fileExtension}`;

        const newFile = new File([file], newFileName, { type: file.type })
        const formData = new FormData()
        formData.append("file", newFile);

        fetch("models/upload.php", {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            saveDataSolicitude(folio)
            console.log("Archivos subidos exitosamente:", data);
        })
        .catch(error => {
            console.error("Error al cargar los archivos", error);
        });
    } else {
        Swal.fire({
            icon: "warning",
            title: "¡Alerta!",
            text: "Seleccione un archivo para subir.",
        })
    }
}

function generateFolio() {
    return new Promise((resolve, reject) => {
        const currentDate = new Date();
        const years = currentDate.getFullYear().toString();
        const year = years.substring(years.length - 2);

        const query = "select COUNT(*) as Cantidad from SCDSIS_Solicitud_registro_mst where fecha between '20"+year+"-01-01 00:00:00.000' and '20"+year+"-12-31 00:00:00.000'";
        $.ajax({
            type: "GET",
            url: "models/consultaDatos.php",
            data: {
                query: query,
            },
            success: function (data) {
                var arr = JSON.parse(data);
                if (arr.length > 0) {
                    const cantidad = parseInt(arr[0].Cantidad, 10) + 1;
                    let aux;
                    if (cantidad < 10) { aux = "-00" + cantidad; }
                    else if (cantidad < 100) { aux = "-0" + cantidad; }
                    else if (cantidad < 10000) { aux = "-" + cantidad; }
                    const folio = year + aux;
                    resolve(folio);
                } else {
                    reject("No se encontraron datos");
                }
            },
            error: function (error) {
                reject(error);
            }
        });
    });
}

function saveDataSolicitude(folio){
    var solicitanteNombre = document.getElementById("inputNombreReloj").value
    var solicitanteNumero = document.getElementById("inputReloj").value
    var departamento = document.getElementById("selectDpto").value
    var correo = document.getElementById("inputCorreo").value
    var encargadoNombre = document.getElementById("inputNombreEncargado").value

    const currentDate = new Date()
    var fecha = currentDate.toISOString().slice(0, 19).replace('T', ' ')

    const selectedRadioTipo = document.querySelector('input[name="tipo"]:checked')
    var tipoSolicitud = selectedRadioTipo ? selectedRadioTipo.value : null

    const selectedRadioTipo2 = document.querySelector('input[name="tipo2"]:checked')
    var tipoTrabajo = selectedRadioTipo2 ? selectedRadioTipo2.value : null

    var sistemaNombre = document.getElementById("inputNombreSistema").value
    var moduloNombre  = document.getElementById("inputNombreModRep").value
    var numeroUsuario = document.getElementById("inputNumeroUsuarios").value
    var departamentos = document.getElementById("inputDepartsInvol").value

    const selectedRadioTipo3 = document.querySelector('input[name="tipo3"]:checked')
    var capacitacion = selectedRadioTipo3 ? selectedRadioTipo3.value : null
    
    var descripcion  = document.getElementById("inputDescripcion").value
    
    var archivo = document.getElementById("fileInput").value
    var rutaArchivo  = "https://172.30.75.22/Apps/wisa/evidencias/" + folio +"."+ extensionArchivoGlobal

    var equipo = document.getElementById("inputEquipo").value
    var software = document.getElementById("inputSoftware").value
    var salario = document.getElementById("inputSalario").value
    var ahorro = document.getElementById("inputAhorro").value
    var roi = document.getElementById("inputROI").value
    
    var area = document.getElementById("inputAreaDepart").value
    
    var antesDir = document.getElementById("inputAntesDir").value
    var antesInd = document.getElementById("inputAntesInd").value
    var antesAdm = document.getElementById("inputAntesAdm").value
    var antesTotal = document.getElementById("inputAntesTotal").value
    
    var despDir = document.getElementById("inputDespDir").value
    var despInd = document.getElementById("inputDespInd").value
    var despAdm = document.getElementById("inputDespAdm").value
    var despTotal = document.getElementById("inputDespTotal").value
    
    var difeDir = document.getElementById("inputDifeDir").value
    var difeInd = document.getElementById("inputDifeInd").value
    var difeAdm = document.getElementById("inputDifeAdm").value
    var difeTotal = document.getElementById("inputDifeTotal").value

    if(numeroUsuario.length > 9){
        Swal.fire({
            icon: "warning",
            title: "!Alerta¡",
            text: "Los usuarios no pueden exceder los 10 caracteres"
        })
        return
    }

    var query = ""

    if(tipoTrabajo === "Nuevo"){

        query = "SP_SCDSIS_INSERTAR_Solicitud_registro_mst '"+folio+"','"+solicitanteNombre+"',"+solicitanteNumero+",'"+departamento+"','"
        +correo+"','"+encargadoNombre+"','"+fecha+"','"+tipoSolicitud+"','"+tipoTrabajo+"','"+sistemaNombre+"','"+moduloNombre+"','"
        +numeroUsuario+"','"+departamentos+"',"+capacitacion+",'"+descripcion+"','"+rutaArchivo+"','"+roi+"','"+area+"',"+equipo+","
        +software+","+salario+","+ahorro+","+antesDir+","+antesInd+","+antesAdm+","+antesTotal+","+despDir+","+despInd+","+despAdm+","
        +despTotal+","+difeDir+","+difeInd+","+difeAdm+","+difeTotal+""

    }else if(tipoTrabajo === "Cambio"){

        query = "SP_SCDSIS_INSERTAR_Solicitud_registro_mst '"+folio+"','"+solicitanteNombre+"',"+solicitanteNumero+",'"+departamento+"','"
        +correo+"','"+encargadoNombre+"','"+fecha+"','"+tipoSolicitud+"','"+tipoTrabajo+"','"+sistemaNombre+"','"+moduloNombre+"','"
        +numeroUsuario+"','"+departamentos+"',"+capacitacion+",'"+descripcion+"','"+rutaArchivo+"','','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0"

    }    
    $.ajax({
        type: "GET",
        url: "models/executaQuery.php",
        data: {
            query: query,
        },
        success: function (data) {
            if(data == "1"){
                sendMail(folio)
            }else{
                Swal.fire({
                    icon: "error",
                    title: "¡Error!",
                    text: "La solicitud no pudo ser registrada correctamente.",
                })
            }
        },
        error: function (error) {
            Swal.fire({
                icon: "error",
                title: "¡Alerta!",
                text: "La solicitud no pudo ser registrada correctamente.",
            })
            console.error(error)
        }
    })
}

function btnSolicitar(){
    const inputIds = [
        "inputReloj",
        "inputNombreReloj",
        "inputCorreo",
        "selectDpto",
        "inputNombreEncargado",
        "inputNombreSistema",
        "inputNombreModRep",
        "inputNumeroUsuarios",
        "inputDepartsInvol",
        "inputDescripcion",
    ]

    var isValid = true

    inputIds.forEach(id => {
        const input = document.getElementById(id)
        if (input && input.value.trim() === "") {
            isValid = false
            input.style.border = "2px solid red"
        } else if (input) {
            input.style.border = ""
        }
    })

    if(tipoTrabajoGlobal === "Nuevo"){
        const inputIdsNuevo = [
            "inputEquipo",
            "inputSoftware",
            "inputSalario",
            "inputAhorro",
            "inputAreaDepart",
            "inputAntesDir",
            "inputAntesInd",
            "inputAntesAdm",
            "inputAntesTotal",
            "inputDespDir",
            "inputDespInd",
            "inputDespAdm",
            "inputDespTotal",
            "inputDifeDir",
            "inputDifeInd",
            "inputDifeAdm",
            "inputDifeTotal"
        ]

        inputIdsNuevo.forEach(id => {
            const input = document.getElementById(id)
            if (input && input.value.trim() === "") {
                isValid = false
                input.style.border = "2px solid red"
            } else if (input) {
                input.style.border = ""
            }
        })

    }

    if (!isValid) {
        Swal.fire({
            icon: "error",
            title: "¡Error!",
            text: "Por favor, complete todos los campos obligatorios.",
        });
        return false;
    }

    generateFolio().then(folio => {
        loadFile(folio)
    }).catch(error => {
        console.error(error)
    })
}

function sendMail(folio){
    const destino = "practicante.sistemas@sewsus.com.mx" //Cambiar por el correo del encargado del departamento  sergio.barron@sewsus.com.mx
    const autor = document.getElementById("inputNombreReloj").value
    const fecha = document.getElementById("inputFecha").value

    const link = "http://smatrsaulocal:8080/Apps/wisa/solicitud_cambio_sistema_revision.php?folio="+folio+"&id=Departamento"

    $.ajax({
        type: "POST",
        url: "enviar_correo.php",
        data: {
            to: destino,
            subject: "Solicitud de cambio o desarrollo de Sistema, "+folio,
            body: "Se ha registrado una Solicitud de cambio o desarrollo de Sistema con folio: "+folio+
            ", realizado por: "+autor+", con fecha: "+fecha+
            ". Ingrese al siguiente link para revisarlo: "+link
        },
        success: function(response) {
            console.log("Email sent successfully")
            Swal.fire({
                icon: "success",
                title: "¡Éxito!",
                text: "La solicitud fue registrada correctamente.",
            }).then((result) => {
                if(result.isConfirmed){
                    window.location.href = "solicitud_cambio_sistema.php"
                }
            })
        },
        error: function(error) {
            console.error("Error sending email", error)
        }
    })
}