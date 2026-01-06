document.addEventListener("DOMContentLoaded",function(){
    document.getElementById("inputCorreo").style.display = "none"

    const params = new URLSearchParams(window.location.search)
    const folio = params.get("folio")
    const id = params.get("id")

    if(id == null){ return }

    const h4 = document.createElement("h4")
    if(id == "AVP"){ h4.innerHTML = "T&S Solicitud de cambio o desarrollo de Sistema Revisión AVP"}
    else if(id == "Departamento"){ h4.innerHTML = "T&S Solicitud de cambio o desarrollo de Sistema Revisión Encargado de Departamento"}

    document.getElementById("pageTitleDiv").appendChild(h4)
    document.getElementById("inputFolio").value = folio
    
    checkEstatus(folio).then((autorizado) => {
        checkStatusFolio(autorizado,id)
    })
})
/*--------------------------------------------------------------------------------------------------------------------------------*/
document.getElementById("btnConfirmarRechazo").addEventListener("click", function(){
    btnRechazar()
})
/*--------------------------------------------------------------------------------------------------------------------------------*/
function checkStatusFolio(estatus, pagina){
    if(estatus == -1){
        Swal.fire({
            icon: "error",
            title: "¡Alerta!",
            text: "El folio: "+document.getElementById("inputFolio").value+" ya ha sido rechazado. ¿Desea verlo de todas formas?",
            showCancelButton: true,
            confirmButtonText: "Si"
        }).then((result) => {
            if(result.isConfirmed){ 
                getData(document.getElementById("inputFolio").value)
            }
        })
    }else if(estatus == 0 && pagina == "Departamento"){
        getData(document.getElementById("inputFolio").value)
        generateButtons(pagina)
    }else if(estatus > 0 && pagina == "Departamento"){
        Swal.fire({
            icon: "info",
            title: "¡Alerta!",
            text: "El folio: "+document.getElementById("inputFolio").value+" ya ha sido autorizado por Departamento. ¿Desea verlo de todas formas?",
            showCancelButton: true,
            confirmButtonText: "Si"
        }).then((result) => {
            if(result.isConfirmed){ 
                getData(document.getElementById("inputFolio").value)
            }
        })
    }else if(estatus > 0 && pagina == "AVP"){
        getData(document.getElementById("inputFolio").value)
        generateButtons(pagina)
    }else if(estatus == 0 && pagina == "AVP"){
        Swal.fire({
            icon: "info",
            title: "¡Alerta!",
            text: "El folio: "+document.getElementById("inputFolio").value+" aún no ha sido autorizado por el Departamento. ¿Desea verlo de todas formas?",
            showCancelButton: true,
            confirmButtonText: "Si"
        }).then((result) => {
            if(result.isConfirmed){ 
                getData(document.getElementById("inputFolio").value)
            }
        })
    }
}
/*--------------------------------------------------------------------------------------------------------------------------------*/
function generateButtons(pagina){
    if(pagina == "AVP"){
        const div = document.createElement("div")
        div.className = "col-md-8"

        const div2 = document.createElement("div")
        div2.className = "col-md-4"

        const btn = document.createElement("input")
        btn.id = "btnAutorizar"
        btn.className = "btn btn-info"
        btn.type = "submit"
        btn.value = "Autorizar Urgencia"
        btn.addEventListener("click", function(){
            const folio = document.getElementById("inputFolio").value
            let query = "UPDATE SCDSIS_Solicitud_registro_mst SET urgente = 1 WHERE folio = '"+folio+"'" 

            $.ajax({
                type: "GET",
                url: "models/executaQuery.php",
                data: {
                    query: query,
                },
                success: function (data) {
                    var arr = JSON.parse(data)
                    if(arr == 1){
                        Swal.fire({
                            icon: "success",
                            title: "¡Éxito!",
                            text: "El folio: "+folio+" fue APROVADO como urgente correctamente.",
                        })
                    }else{
                        Swal.fire({
                            icon: "error",
                            title: "¡Error!",
                            text: "Ocurrió un error al rechazar el folio.",
                        })
                    }
                },
                error: function(data){
                    console.log("Error: "+data)
                }
            })
        })
        div.appendChild(btn)

        const btn2 = document.createElement("input")
        btn2.id = "btnRechazar"
        btn2.className = "btn btn-info"
        btn2.type = "submit"
        btn2.value = "Rechazar Urgencia"
        btn2.addEventListener("click", function(){
            const folio = document.getElementById("inputFolio").value
            let query = "UPDATE SCDSIS_Solicitud_registro_mst SET urgente = 0 WHERE folio = '"+folio+"'" 

            $.ajax({
                type: "GET",
                url: "models/executaQuery.php",
                data: {
                    query: query,
                },
                success: function (data) {
                    var arr = JSON.parse(data)
                    if(arr == 1){
                        Swal.fire({
                            icon: "success",
                            title: "¡Éxito!",
                            text: "El folio: "+folio+" fue NEGADO como urgente correctamente.",
                        })
                    }else{
                        Swal.fire({
                            icon: "error",
                            title: "¡Error!",
                            text: "Ocurrió un error al rechazar el folio.",
                        })
                    }
                },
                error: function(data){
                    console.log("Error: "+data)
                }
            })
        })
        div2.appendChild(btn2)

        document.getElementById("botonesDiv").appendChild(div)
        document.getElementById("botonesDiv").appendChild(div2)
    }else{
        const div = document.createElement("div")
        div.className = "col-md-2"

        const div2 = document.createElement("div")
        div2.className = "col-md-8"

        const div3 = document.createElement("div")
        div3.className = "col-md-1"

        const btn = document.createElement("input")
        btn.id = "btnAutorizar"
        btn.className = "btn btn-info"
        btn.type = "submit"
        btn.value = "Autorizar"
        btn.addEventListener("click", function(){
            const folio = document.getElementById("inputFolio").value
            query = "UPDATE SCDSIS_Solicitud_registro_mst SET autorizado = 1 WHERE folio = '"+folio+"'"
            $.ajax({
                type: "GET",
                url: "models/executaQuery.php",
                data: {
                    query: query,
                },
                success: function (data) {
                    var arr = JSON.parse(data)
                    if(arr == 1){
                        mailAuthorizeDpto("Normal")
                    }else{
                        Swal.fire({
                            icon: "error",
                            title: "¡Error!",
                            text: "Ocurrió un error al rechazar el folio.",
                        })
                    }
                },
                error: function(data){
                    console.log("Error: "+data)
                }
            })
        })
        div.appendChild(btn)

        const btn2 = document.createElement("input")
        btn2.id = "btnRechazar"
        btn2.className = "btn btn-info"
        btn2.type = "submit"
        btn2.value = "Autorizar como urgente"
        btn2.addEventListener("click", function(){
            const folio = document.getElementById("inputFolio").value

            let query = "UPDATE SCDSIS_Solicitud_registro_mst SET autorizado = 1 WHERE folio = '"+folio+"'"
            $.ajax({
                type: "GET",
                url: "models/executaQuery.php",
                data: {
                    query: query,
                },
                success: function (data) {
                    var arr = JSON.parse(data)
                    if(arr == 1){
                        mailAuthorizeDpto("Urgente")
                    }else{
                        Swal.fire({
                            icon: "error",
                            title: "¡Error!",
                            text: "Ocurrió un error al rechazar el folio.",
                        })
                    }
                },
                error: function(data){
                    console.log("Error: "+data)
                }
            })
        })
        div2.appendChild(btn2)

        const btn3 = document.createElement("input")
        btn3.id = "btnRechazar"
        btn3.className = "btn btn-info"
        btn3.type = "submit"
        btn3.value = "Rechazar"
        btn3.addEventListener("click", function(){
            openModal()
        })
        div3.appendChild(btn3)

        document.getElementById("botonesDiv").appendChild(div)
        document.getElementById("botonesDiv").appendChild(div2)
        document.getElementById("botonesDiv").appendChild(div3)
    }
}
/*--------------------------------------------------------------------------------------------------------------------------------*/
function getData(folio){
    query = "select * from SCDSIS_Solicitud_registro_mst a, SCDSIS_Solicitud_ROI b where a.folio = b.folio and a.folio = '"+folio+"'"
    $.ajax({
        type: "GET",
        url: "models/consultaDatos.php",
        data: {
            query: query,
        },
        success: function (data) {
            var arr = JSON.parse(data)
            if(arr.length > 0){
                document.getElementById("inputNombre").value = arr[0].nombreSolicitante
                document.getElementById("inputReloj").value = arr[0].numeroSolicitante
                document.getElementById("inputDepartamento").value = arr[0].departamento
                document.getElementById("inputCorreo").value = arr[0].correoEncargado
                document.getElementById("inputNombreEncargado").value = arr[0].nombreEncargado
                document.getElementById("inputFecha").value = arr[0].fecha.date.substring(0, 10)
                document.getElementById("inputTipoSolicitud").value = arr[0].tipoSolicitud
                document.getElementById("inputTipoTrabajo").value = arr[0].tipoTrabajo
                document.getElementById("inputNombreSistema").value = arr[0].nombreSistema
                document.getElementById("inputNombreModRep").value = arr[0].nombreModulo
                document.getElementById("inputNumeroUsuarios").value = arr[0].numeroUsuarios
                document.getElementById("inputDepartsInvol").value = arr[0].dptsInvolucrados
                
                const capacitacion = arr[0].requiereCapacitacion == 1 ? "Si" : "No"
                document.getElementById("inputCapacitacion").value = capacitacion
                
                document.getElementById("inputDescripcion").value = arr[0].descripcionSolicitud
                document.getElementById("inputEquipo").value = arr[0].equipo
                document.getElementById("inputSoftware").value = arr[0].software
                document.getElementById("inputSalario").value = arr[0].salario
                document.getElementById("inputAhorro").value = arr[0].ahorro
                document.getElementById("inputROI").value = arr[0].ROI
                document.getElementById("inputAreaDepart").value = arr[0].area
                document.getElementById("inputAntesDir").value = arr[0].antesDirectos
                document.getElementById("inputAntesInd").value = arr[0].antesIndirectos
                document.getElementById("inputAntesAdm").value = arr[0].antesAdmin
                document.getElementById("inputAntesTotal").value = arr[0].antesTotal
                document.getElementById("inputDespDir").value = arr[0].despuesDirectos
                document.getElementById("inputDespInd").value = arr[0].despuesIndirectos
                document.getElementById("inputDespAdm").value = arr[0].despuesAdmin
                document.getElementById("inputDespTotal").value = arr[0].despuesTotal
                document.getElementById("inputDifeDir").value = arr[0].diferenciaDirectos
                document.getElementById("inputDifeInd").value = arr[0].diferenciaIndirectos
                document.getElementById("inputDifeAdm").value = arr[0].diferenciaAdmin
                document.getElementById("inputDifeTotal").value = arr[0].diferenciaTotal
            }else{
                Swal.fire({
                    icon: "error",
                    title: "¡Error!",
                    text: "No se encontró el Folio: "+document.getElementById("inputFolio").value,
                })
            }
        },
        error: function (error){
            console.log(error)
        }
    })
}
/*--------------------------------------------------------------------------------------------------------------------------------*/
function seeFile(){
    const folio = document.getElementById("inputFolio").value
    let query = "SELECT archivoUbicacion from SCDSIS_Solicitud_registro_mst where folio = '"+folio+"'"
    $.ajax({
        type: "GET",
        url: "models/consultaDatos.php",
        data: {
            query: query,
        },
        success: function (data) {
            var arr = JSON.parse(data)
            if(arr.length > 0){
                const filePath = arr[0].archivoUbicacion
                window.open(filePath, "_blank")
            }
        },
        error: function (error){
            console.log(error)
        }
    })
}

/*--------------------------------------------------------------------------------------------------------------------------------*/
function btnRechazar(){
    const folio = document.getElementById("inputFolio").value
    var motivo = document.getElementById("inputRechazoMotivo").value
    var usuario = document.getElementById("inputNombreEncargado").value

    if(motivo == ""){
        Swal.fire({
            icon: "error",
            title: "¡Error!",
            text: "Escriba un motivo.",
        })
        return
    }
    if(motivo.length > 50){
        Swal.fire({
            icon: "error",
            title: "¡Error!",
            text: "El motivo no debe superar los 50 caracteres.",
        })
        return
    }

    query = "SP_SCDSIS_Solicitud_registro_rechazo '"+folio+"','"+motivo+"','"+usuario+"','Rechazo',"+(-1)+";"
    $.ajax({
        type: "GET",
        url: "models/executaQuery.php",
        data: {
            query: query,
        },
        success: function (data) {
            var arr = JSON.parse(data)
            if(arr == 1){
                Swal.fire({
                    icon: "success",
                    title: "¡Éxito!",
                    text: "El folio: "+document.getElementById("inputFolio").value+" fue rechazado correctamente.",
                }).then((result) => {
                    if(result.isConfirmed){
                        window.location.href = "solicitud_cambio_sistema_revision.php"
                    }
                })
            }else{
                Swal.fire({
                    icon: "error",
                    title: "¡Error!",
                    text: "Ocurrió un error al rechazar el folio.",
                })
            }
        },
        error: function(data){
            console.log("Error: "+data)
        }
    })
}
/*--------------------------------------------------------------------------------------------------------------------------------*/
function checkEstatus(folio){
    return new Promise((resolve, reject) => {
        query = "SELECT autorizado FROM SCDSIS_Solicitud_registro_mst where folio = '"+folio+"'"
        $.ajax({
            type: "GET",
            url: "models/consultaDatos.php",
            data: {
                query: query,
            },
            success: function (data) {
                var arr = JSON.parse(data)
                if (arr.length > 0){
                    resolve(arr[0].autorizado)
                }
            },
            error: function (data){
                console.log("Error: "+data)
                reject("Error: "+data)
            }
        })
    })
}
/*--------------------------------------------------------------------------------------------------------------------------------*/

// Modal
const modal = document.getElementById('myModal');
const closeModalButton = document.getElementById('closeModal');


function openModal() {
  modal.style.display = 'block';
}


function closeModal() {
  modal.style.display = 'none';
}

closeModalButton.addEventListener('click', closeModal);

window.addEventListener('click', (event) => {
  if (event.target === modal) {
    closeModal();
  }
});

// Correos
/*--------------------------------------------------------------------------------------------------------------------------------*/
function mailAuthorizeDpto(tipo){
    const folio = document.getElementById("inputFolio").value
    const destino = "practicante.sistemas@sewsus.com.mx"//cambiar por SISTEMAS sergio.barron@sewsus.com.mx
    const autor = document.getElementById("inputNombre").value
    const fecha = document.getElementById("inputFecha").value

    $.ajax({
        type: "POST",
        url: "enviar_correo.php",
        data: {
            to: destino,
            subject: "Solicitud de cambio o desarrollo de Sistema, "+folio,
            body: "Se ha aprobado una Solicitud de cambio o desarrollo de Sistema con folio: "+folio+
            ", realizado por: "+autor+", con fecha: "+fecha+
            ". Ingrese al siguiente link para revisarlo: http://smatrsaulocal:8080/Apps/wisa/solicitud_cambio_sistema_planTrabajo.php?folio="+folio+"&id=Sistemas"
        },
        success: function(response) {
            console.log("Email sent successfully")
            if(tipo == "Urgente"){
                mailAuthorizeUrgent()
            }else{
                Swal.fire({
                    icon: "success",
                    title: "¡Éxito!",
                    text: "El folio: "+document.getElementById("inputFolio").value+" fue autorizado correctamente.",
                }).then((result) => {
                    if(result.isConfirmed){
                        window.location.href = "solicitud_cambio_sistema_revision.php"
                    }
                })
            }
        },
        error: function(error) {
            console.error("Error sending email", error)
        }
    })
}

function mailAuthorizeUrgent(){
    const folio = document.getElementById("inputFolio").value
    const destino = "practicante.sistemas@sewsus.com.mx"//cambiar por AVP sergio.barron@sewsus.com.mx
    const autor = document.getElementById("inputNombre").value
    const fecha = document.getElementById("inputFecha").value

    const link = "http://smatrsaulocal:8080/Apps/wisa/solicitud_cambio_sistema_revision.php?folio="+folio+"&id=AVP"

    $.ajax({
        type: "POST",
        url: "enviar_correo.php",
        data: {
            to: destino,
            subject: "Solicitud de cambio o desarrollo de Sistema, "+folio+" (URGENTE)",
            body: "Se ha aprobado URGENTEMENTE una Solicitud de cambio o desarrollo de Sistema con folio: "+folio+
            ", realizado por: "+autor+", con fecha: "+fecha+
            ". Ingrese al siguiente link para revisarlo: "+link
        },
        success: function(response) {            
            console.log("Email sent successfully")
            Swal.fire({
                icon: "success",
                title: "¡Éxito!",
                text: "El folio: "+document.getElementById("inputFolio").value+" fue autorizado correctamente como urgente.",
            }).then((result) => {
                if(result.isConfirmed){
                    window.location.href = "solicitud_cambio_sistema_revision.php"
                }
            })
        },
        error: function(error) {
            Swal.fire({
                icon: "error",
                title: "¡Error!",
                text: "Ocurrió un error al autorizar el folio: "+document.getElementById("inputFolio").value+" correctamente como urgente.",
            })
            console.error("Error sending email", error)
        }
    })
}