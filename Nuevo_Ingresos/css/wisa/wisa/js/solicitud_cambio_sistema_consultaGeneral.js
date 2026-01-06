document.addEventListener("DOMContentLoaded",function(){
    var text_max = 200
    $('#count_message').html('0 / ' + text_max )
    $('#inputDescripcion').keyup(function() {
        let text_length = $('#inputDescripcion').val().length
        
        $('#count_message').html(text_length + ' / ' + text_max)
    })

    var dict_Estatus = {
        10: "Solicitud Rechazada",
        0: "Solicitud Nueva",
        1: "Autorizado por el Departamento",
        2: "Autorizado por Desarrollo",
        3: "Autorización Completa",
        4: "Aprobada Completamente",
        5: "Liberado por Requisitor"
    }
    const urlParams = new URLSearchParams(window.location.search)
    const folio = urlParams.get("folio")

    if(folio != null){ 
        document.getElementById("selectFolio").value = folio
        document.getElementById("inputFolio").value = folio
        checkStatusFolio(folio)
        checkUrgencia(folio) 
    }

    document.getElementById("inputCorreo").style.display = "none"  
    document.getElementById("inputFolio").style.display = "none"
    
    document.getElementById('btnGoTop').onclick = function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    
    document.getElementById('btnGoBottom').onclick = function() {
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
    }

    document.addEventListener("change", function(event){
        if(event.target.name === "proyectoFactible" && event.target.value === "1" && event.target.checked){
            document.getElementById("inputRazonSis").disabled = true
            document.getElementById("inputRazonSis").value = ""
    
        }else if(event.target.name === "proyectoFactible" && event.target.value === "0" && event.target.checked){
            document.getElementById("inputRazonSis").disabled = false
        }
    
        if(event.target.name === "juntasProgramadas" && event.target.value === "1" && event.target.checked){
            document.getElementById("btnFechas").disabled = false
            
        }else if(event.target.name === "juntasProgramadas" && event.target.value === "0" && event.target.checked){
            document.getElementById("btnFechas").disabled = true
        }
    })
    
    document.getElementById("selectFolio").addEventListener("change", function(event){
        var folio = document.getElementById("selectFolio").value
    
        document.getElementById("inputFolio").value = folio
        checkStatusFolio(folio)
        checkUrgencia(folio)
    })
    
    function checkUrgencia(folio){
        var query = "SELECT urgente FROM SCDSIS_Solicitud_registro_mst WHERE folio = '"+folio+"'"
        $.ajax({
            type: "GET",
            url: "models/consultaDatos.php",
            data: {
                query: query,
            },
            success: function (data) {
                var arr = JSON.parse(data)
                if(arr.length > 0){
                    if(arr[0].urgente == 1){ 
                        document.getElementById("inputUrgente").value = "PRIORIDAD" 
                        document.getElementById("inputUrgente").style.color = "red"
                        document.getElementById("inputUrgente").style.textAlign = "center"
                    }
                    else{ 
                        document.getElementById("inputUrgente").value = "Normal" 
                        document.getElementById("inputUrgente").style.color = "black"
                        document.getElementById("inputUrgente").style.textAlign = "center"
                    }
                }else{ document.getElementById("selectFolio").value = "" }
            }
        })
    }
    
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
                        text: "No se encontró el folio: "+document.getElementById("inputFolio").value+"."
                    })
                }
            },
            error: function (error){
                console.log(error)
            }
        })
    }
    
    function getDataPlanMst(folio){
        query = "select * from [dbo].[SCDSIS_Solicitud_plan_trabajo_mst] where folio = '"+folio+"'"
        $.ajax({
            type: "GET",
            url: "models/consultaDatos.php",
            data: {
                query: query,
            },
            success: function (data) {
                var arr = JSON.parse(data)
                if(arr.length > 0){
                    const factible = arr[0].proyectoFactible == 1 ? "Si" : "No"
                    document.getElementById("proyectoFactible").value = factible
                    document.getElementById("proyectoRazon").value = arr[0].razon
                    document.getElementById("proyectoTicket").value = arr[0].ticketAsignado
                    document.getElementById("proyectoDuracion").value = arr[0].duracionProyecto
                    document.getElementById("proyectoInicio").value = arr[0].inicio.date.substring(0, 10)
                    document.getElementById("proyectoFin").value = arr[0].fin.date.substring(0, 10)
                    document.getElementById("proyectoNoEmpleado").value = arr[0].responsable
                    document.getElementById("proyectoEmpleado").value = arr[0].numeroEmpleado
    
                    const juntas = arr[0].juntasProgramadas == 1 ? "Si" : "No"
                    document.getElementById("proyectoJuntas").value = juntas
                    
                    document.getElementById("proyectoRecursos").value = arr[0].recursosRequeridos
                }else{
                    Swal.fire({
                        icon: "info",
                        title: "¡Aviso!",
                        text: "El folio: "+document.getElementById("inputFolio").value+", todavía no tiene un plan de trabajo."
                    })
                }
            },
            error: function (error){
                console.log(error)
            }
        })
    }
    
    function getDataPlanJuntas(folio){
        document.getElementById("tbodydivJuntas").innerHTML = ""
        query = "select * from [dbo].[SCDSIS_Solicitud_plan_trabajo_juntas_programadas] where folio = '"+folio+"'"
        $.ajax({
            type: "GET",
            url: "models/consultaDatos.php",
            data: {
                query: query,
            },
            success: function (data) {
                var arr = JSON.parse(data)
                if(arr.length > 0){
                    for(i of arr){
                        const tr = document.createElement("tr")
    
                        const fecha = document.createElement("td")
                        fecha.innerHTML = i.fecha.date.substring(0, 10)
                        tr.appendChild(fecha)
    
                        document.getElementById("tbodydivJuntas").appendChild(tr)
                    }
                }else{
                }
            },
            error: function (error){
                console.log(error)
            }
        })
    }
    
    function getDataPlanProyectos(folio){
        document.getElementById("tbodydivProyectos").innerHTML = ""
        query = "select * from [dbo].[SCDSIS_Solicitud_plan_trabajo_proyectos_previos] where folio = '"+folio+"'"
        $.ajax({
            type: "GET",
            url: "models/consultaDatos.php",
            data: {
                query: query,
            },
            success: function (data) {
                var arr = JSON.parse(data)
                if(arr.length > 0){
                    for(i of arr){
                        const tr = document.createElement("tr")
    
                        const proyecto = document.createElement("td")
                        proyecto.innerHTML = i.nombreProyecto
                        tr.appendChild(proyecto)
    
                        const duracion = document.createElement("td")
                        duracion.innerHTML = i.duracion
                        tr.appendChild(duracion)
    
                        const fecha = document.createElement("td")
                        fecha.innerHTML = i.fechaTermino.date.substring(0, 10)
                        tr.appendChild(fecha)
    
                        document.getElementById("tbodydivProyectos").appendChild(tr)
                    }
                    
                }else{
    
                }
            },
            error: function (error){
                console.log(error)
            }
        })
    }
    
    function checkStatusFolio(folio){
        document.getElementById("divRechazo").innerHTML = ""
        document.getElementById("consultaGeneralBtnDiv").innerHTML = ""
        query = "select autorizado from SCDSIS_Solicitud_registro_mst where folio = '"+folio+"'"
        $.ajax({
            type: "GET",
            url: "models/consultaDatos.php",
            data: {
                query: query,
            },
            success: function (data) {
                var arr = JSON.parse(data)
                if(arr.length > 0){
                    document.getElementById("inputEstado").style.color = "black"
                    document.getElementById("inputEstado").style.textAlign = "center"
                    if(arr[0].autorizado == -1){ document.getElementById("inputEstado").value = dict_Estatus[10] }
                    else{ document.getElementById("inputEstado").value = dict_Estatus[arr[0].autorizado] }
                    
                    if(arr[0].autorizado == -1){
                        Swal.fire({
                            icon: "error",
                            title: "¡Alerta!",
                            text: "El folio: "+document.getElementById("inputFolio").value+" ya ha sido rechazado. ¿Desea verlo de todas formas?",
                            showCancelButton: true,
                            confirmButtonText: "Si"
                        }).then((result) => {
                            if(result.isConfirmed){ 
                                getData(document.getElementById("inputFolio").value)
                                seeRechazo(document.getElementById("inputFolio").value)
                            }
                        })
                         
                    }else if(arr[0].autorizado == 0){
                        Swal.fire({
                            icon: "warning",
                            title: "¡Alerta!",
                            text: "El folio: "+document.getElementById("inputFolio").value+" Aún no ha sido autorizado por el encargado del departamento. ¿Desea verlo de todas formas?",
                            showCancelButton: true,
                            confirmButtonText: "Si"
                        }).then((result) => {
                            if(result.isConfirmed){ 
                                getData(document.getElementById("inputFolio").value)
                            }
                        })
                         
                    }else if(arr[0].autorizado == 1){
                        Swal.fire({
                            icon: "warning",
                            title: "¡Alerta!",
                            text: "El folio: "+document.getElementById("inputFolio").value+" Aún no ha sido autorizado por el encargado del departamento de Sistemas. ¿Desea verlo de todas formas?",
                            showCancelButton: true,
                            confirmButtonText: "Si"
                        }).then((result) => {
                            if(result.isConfirmed){ 
                                getData(document.getElementById("inputFolio").value)
                            }
                        })
                         
                    }else if(arr[0].autorizado == 2){
                        Swal.fire({
                            icon: "warning",
                            title: "¡Alerta!",
                            text: "El folio: "+document.getElementById("inputFolio").value+" Aún no ha sido autorizado por MNJ Sistemas. ¿Desea verlo de todas formas?",
                            showCancelButton: true,
                            confirmButtonText: "Si"
                        }).then((result) => {
                            if(result.isConfirmed){ 
                                getData(document.getElementById("inputFolio").value)
                            }
                        })
                         
                    }else if(arr[0].autorizado == 3){
                        getData(document.getElementById("inputFolio").value)
                        getDataPlanMst(document.getElementById("inputFolio").value)
                        getDataPlanJuntas(document.getElementById("inputFolio").value)
                        getDataPlanProyectos(document.getElementById("inputFolio").value)
                        
                        const btn = document.createElement('input')
                        btn.id = 'btnTerminar'
                        btn.className = 'btn btn-info'
                        btn.type = 'submit'
                        btn.name = 'btnTerminar'
                        btn.value = 'Terminar'
                        btn.onclick = function() { terminarSolicitud() }

                        document.getElementById("consultaGeneralBtnDiv").appendChild(btn)
                    }else if(arr[0].autorizado == 4){
                        getData(document.getElementById("inputFolio").value)
                        getDataPlanMst(document.getElementById("inputFolio").value)
                        getDataPlanJuntas(document.getElementById("inputFolio").value)
                        getDataPlanProyectos(document.getElementById("inputFolio").value)

                        const btn = document.createElement('input')
                        btn.id = 'btnRecibido'
                        btn.className = 'btn btn-info'
                        btn.type = 'submit'
                        btn.name = 'btnRecibido'
                        btn.value = 'Liberar Solicitud'
                        btn.onclick = function() { liberarSolicitud() }

                        document.getElementById("consultaGeneralBtnDiv").appendChild(btn)
                    }else if(arr[0].autorizado == 5){
                        getData(document.getElementById("inputFolio").value)
                        getDataPlanMst(document.getElementById("inputFolio").value)
                        getDataPlanJuntas(document.getElementById("inputFolio").value)
                        getDataPlanProyectos(document.getElementById("inputFolio").value)
                        
                        const label = document.createElement("label")
                        label.innerText = "Solicitud Liberada"

                        document.getElementById("consultaGeneralBtnDiv").appendChild(label)
                    }
                }else{
                    Swal.fire({
                        icon: "error",
                        title: "¡Error!",
                        text: "El folio: "+document.getElementById("inputFolio").value+" no existe.",
                    })
                }
            },
            error: function (error){
                console.log(error)
            }
        })
    }
})

function liberarSolicitud(){
    let query = "UPDATE [dbo].[SCDSIS_Solicitud_registro_mst] SET autorizado = 5 WHERE folio = '"+document.getElementById("inputFolio").value+"'"
    $.ajax({
        type: "GET",
        url: "models/consultaDatos.php",
        data: {
            query: query,
        },
        success: function (data) {
            var arr = JSON.parse(data)
            Swal.fire({
                icon: "success",
                title: "Solicitud Liberada."
            }).then((result) => {
                if(result.isConfirmed){ window.location.href = "solicitud_cambio_sistema_consultaGeneral.php" }
            })
        }
    })
}

function seeFile(){
    const folio = document.getElementById("inputFolio").value

    query = "SELECT archivoUbicacion from SCDSIS_Solicitud_registro_mst where folio = '"+folio+"'"

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

function terminarSolicitud(){
    let query = "UPDATE [dbo].[SCDSIS_Solicitud_registro_mst] SET autorizado = 4 WHERE folio = '"+document.getElementById("inputFolio").value+"'"
    $.ajax({
        type: "GET",
        url: "models/executaQuery.php",
        data: {
            query: query,
        },
        success: function(data){
            let arr = JSON.parse(data)
            if(arr == 1){ getNumeroSolicitante(document.getElementById("inputFolio").value) }
        }
    })
}

function getNumeroSolicitante(folio){
    let query = "SELECT numeroSolicitante AS reloj FROM [dbo].[SCDSIS_Solicitud_registro_mst] WHERE folio = '"+folio+"'"
    $.ajax({
        type: "GET",
        url: "models/consultaDatos.php",
        data: {
            query: query,
        },
        success: function (data) {
            var arr = JSON.parse(data)
            getMailSolicitante(arr[0].reloj)
        }
    })
}

function getMailSolicitante(reloj){
    let query = "SP_ERP_OBTIENE_CORREO "+reloj+";"
    $.ajax({
        type: "GET",
        url: "models/consultaDatos.php",
        data: {
            query: query,
        },
        success: function (data) {
            var arr = JSON.parse(data)
            if(arr.length > 0){ mailSolicitante(arr[0].Correo) }
            else{ mailSolicitante("") }
        }
    })
}

function mailSolicitante(mail){
    if(mail == ""){
        Swal.fire({
            icon: "success",
            title: "¡Solicitud Terminada!",
            text: "La solicitud fue aprobada correctamente."
        }).then((result) => {
            if(result.isConfirmed){ window.location.href = "solicitud_cambio_sistema_consultaGeneral.php" }
        })
    }else{
        const folio = document.getElementById("inputFolio").value
        const destino = mail
        const fecha = document.getElementById("inputFecha").value

        $.ajax({
            type: "POST",
            url: "enviar_correo.php",
            data: {
                to: destino,
                subject: "Solicitud de cambio o desarrollo de Sistema con folio: "+folio+" ha sido terminado y APROVADO.",
                body: "Se ha aprobado una Solicitud de cambio o desarrollo de Sistema con folio: "+folio+
                ", realizado por: "+autor+", con fecha: "+fecha+". Ingrese al siguiente link para checarlo: http://smatrsaulocal:8080/Apps/wisa/solicitud_cambio_sistema_menuList.php"
            },
            success: function(response) {
                Swal.fire({
                    icon: "success",
                    title: "¡Solicitud Terminada!",
                    text: "La solicitud fue correctamente y un correo se envió al solicitante."
                }).then((result) => {
                    if(result.isConfirmed){ window.location.href = "solicitud_cambio_sistema_consultaGeneral.php" }
                })   
            }
        })
    }
}

function seeRechazo(folio){
    var query = "SELECT * FROM [dbo].[SCDSIS_Solicitud_registro_autorizaciones] WHERE folio = '"+folio+"'"
    $.ajax({
        type: "GET",
        url: "models/consultaDatos.php",
        data: {
            query: query,
        },
        success: function (data) {
            var arr = JSON.parse(data)
            if(arr.length > 0){
                const table = document.createElement("table")
                table.setAttribute("class", "table table-bordered")

                const motivoRow = document.createElement("tr")
                const motivoLabel = document.createElement("td")
                motivoLabel.textContent = "Motivo:"
                const motivoValue = document.createElement("td")
                motivoValue.textContent = arr[0].motivo
                motivoRow.appendChild(motivoLabel)
                motivoRow.appendChild(motivoValue)

                const usuarioRow = document.createElement("tr")
                const usuarioLabel = document.createElement("td")
                usuarioLabel.textContent = "Usuario:"
                const usuarioValue = document.createElement("td")
                usuarioValue.textContent = arr[0].usuario
                usuarioRow.appendChild(usuarioLabel)
                usuarioRow.appendChild(usuarioValue)

                const tipoRow = document.createElement("tr")
                const tipoLabel = document.createElement("td")
                tipoLabel.textContent = "Tipo:"
                const tipoValue = document.createElement("td")
                tipoValue.textContent = arr[0].tipo
                tipoRow.appendChild(tipoLabel)
                tipoRow.appendChild(tipoValue)

                const fechaRow = document.createElement("tr")
                const fechaLabel = document.createElement("td")
                fechaLabel.textContent = "Fecha:"
                const fechaValue = document.createElement("td")
                fechaValue.textContent = arr[0].fecha.date.substring(0, 10)
                fechaRow.appendChild(fechaLabel)
                fechaRow.appendChild(fechaValue)

                table.appendChild(motivoRow);
                table.appendChild(usuarioRow);
                table.appendChild(tipoRow);
                table.appendChild(fechaRow);

                document.getElementById("divRechazo").appendChild(table)
            }
        }
    })
}

