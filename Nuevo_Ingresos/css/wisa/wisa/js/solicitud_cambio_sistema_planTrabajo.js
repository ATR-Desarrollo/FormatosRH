document.addEventListener("DOMContentLoaded",function(){
    document.getElementById("inputCorreo").style.display = "none"  
    document.getElementById("inputFolio").style.display = "none"

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

    var factibleGlobal = "Si"
    var result = []

    const urlParams = new URLSearchParams(window.location.search)
    const folio = urlParams.get("folio")
    const id = urlParams.get("id")

    if(id == null){ return }

    const h4 = document.createElement("h4")
    if(id == "MNJ"){ h4.innerHTML = "T&S Solicitud de cambio o desarrollo de Sistema Revisión MNJ Sistemas"}
    else if(id == "Sistemas"){ h4.innerHTML = "T&S Solicitud de cambio o desarrollo de Sistema Revisión Sistemas"}
    document.getElementById("pageTitleDiv").appendChild(h4)

    if(folio != null){ 
        document.getElementById("inputFolio").value = folio
        document.getElementById("selectFolio").value = folio
        
        checkStatusFolio(folio,id)
        checkUrgencia(folio) 
    }

    const today = new Date().toISOString().split('T')[0]
    document.getElementById("inputInicioSis").setAttribute('min', today)
    document.getElementById("inputFinSis").setAttribute('min', today)

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
            factibleGlobal = "Si"

            const inputIds = [
                "inputRazonSis",
                "inputTicketSis",
                "inputInicioSis",
                "inputFinSis",
                "inputNumeroEmpleadoSis",
                "inputResponsableSis",
                "inputRecursosSis",
                "selectDia"
            ]

            const inputIdsNuevo = [
                "inputRazonSis",
            ]

            inputIds.forEach(id => {
                document.getElementById(id).style.border = ""
            })

            inputIdsNuevo.forEach(id => {
                document.getElementById(id).style.border = ""
            })

        }else if(event.target.name === "proyectoFactible" && event.target.value === "0" && event.target.checked){
            document.getElementById("inputRazonSis").disabled = false

            factibleGlobal = "No"

            const inputIds = [
                "inputRazonSis",
                "inputTicketSis",
                "inputInicioSis",
                "inputFinSis",
                "inputNumeroEmpleadoSis",
                "inputResponsableSis",
                "inputRecursosSis",
                "selectDia"
            ]

            const inputIdsNuevo = [
                "inputRazonSis",
            ]

            inputIds.forEach(id => {
                document.getElementById(id).style.border = ""
            })

            inputIdsNuevo.forEach(id => {
                document.getElementById(id).style.border = ""
            })
        }
    })

    document.getElementById("inputNumeroEmpleadoSis").addEventListener("change", function(event){
        searchName()
    })
    
    document.getElementById("selectFolio").addEventListener("change", function(event){
        if(this.value == ""){ 
            document.getElementById("inputFinSis").disabled = true
            document.getElementById("selectDia").disabled = true
            return 
        }
        let folio = this.value
        document.getElementById("inputFolio").value = folio
        checkStatusFolio(folio,id)
        checkUrgencia(folio)
    })

    document.getElementById("inputNumeroEmpleadoSis").addEventListener("change", function(event){
        searchName()
    })

    document.getElementById("inputInicioSis").addEventListener("change", function(event){
        document.getElementById("inputFinSis").disabled = false
    })

    document.getElementById("inputFinSis").addEventListener("change", function(event){
        document.getElementById("selectDia").disabled = false
        document.getElementById("selectDia").value = "0"
    })

    document.getElementById("selectDia").addEventListener("change", function(){
        if(this.value == ""){ return }

        document.getElementById("fechas").innerHTML = ""
        result.length = 0

        const date1 = new Date(document.getElementById("inputInicioSis").value)
        const date2 = new Date(document.getElementById("inputFinSis").value)

        const diffMs = Math.abs(date2 - date1)
        const diffWeeks = diffMs / ( 1000 * 60 * 60 * 24 * 7 )

        getWeeklyDates(date1,date2,this.value)
    })

    function getWeeklyDates(startStr, endStr, day){
    
        let current = new Date(startStr)
        const end = new Date(endStr)

        let jsDay = day % 7
        if(jsDay === 0){ jsDay = 7 }
        const currentDay = current.getDay() === 0 ? 7 : current.getDay()
        let diffToTarget = (jsDay - currentDay + 7) % 7
        if(diffToTarget === 0){ diffToTarget = 7 }
        current.setDate(current.getDate()+diffToTarget)

        while(current <= end){
            const yyyy = current.getFullYear()
            const mm = String(current.getMonth() + 1).padStart(2, '0')
            const dd = String(current.getDate()).padStart(2, '0')
            result.push(`${yyyy}-${mm}-${dd}`)
            current.setDate(current.getDate() + 7)
        }

        const label = document.createElement("label")
        label.className = ""
        label.innerHTML = "Fechas de las juntas:"

        document.getElementById("fechas").appendChild(label)
        
        for(let i = 0; i<result.length ;i++){
            const row = document.createElement("tr")

            const date = document.createElement("label")
            date.innerHTML = result[i]
            row.appendChild(date)

            document.getElementById("fechas").appendChild(row)
        }
    }

    document.getElementById("btnConfirmarRechazo").addEventListener("click", function(){
        if(id == "MNJ"){ btnRechazarSistemasMNJ() }
        else{ btnRechazarSistemas() }
    })

    document.getElementById("btnConfirmarCambioPlan").addEventListener("click", function(){
        modalCambio.style.display = 'none';
        if(document.getElementById("inputCambioPlanMotivo").value == ""){
            Swal.fire({
                icon: "warning",
                title: "Escriba un motivo para solicitar el cambio de plan.",
            })
            return
        }
        let motivo = document.getElementById("inputCambioPlanMotivo").value

        btnSolicitarCambioPlanTrabajo(motivo)
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
    
    function searchName(){
        var reloj = document.getElementById("inputNumeroEmpleadoSis").value
        if(reloj == ""){
            Swal.fire({
                icon: "warning",
                title: "¡Alerta!",
                text: "Escriba el número del empleado.",
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
                    document.getElementById("inputResponsableSis").value = arr[0].PRETTYNAME
                }else{
                    Swal.fire({
                        icon: "error",
                        title: "¡Error!",
                        text: "No se encontró el número de empleado.",
                    })
                    document.getElementById("inputResponsableSis").value = ""
                    document.getElementById("inputNumeroEmpleadoSis").value = ""
                }
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

    function getDataPlanSistemas(folio){
        query = "SELECT * from SCDSIS_Solicitud_plan_trabajo_mst where folio = '"+folio+"'"
        $.ajax({
            type: "GET",
            url: "models/consultaDatos.php",
            data: {
                query: query,
            },
            success: function (data) {
                var arr = JSON.parse(data)
                document.getElementById("inputRazonSis").value = ""
                document.getElementById("inputTicketSis").value = ""
                document.getElementById("inputInicioSis").value = ""
                document.getElementById("inputFinSis").value = ""
                document.getElementById("inputNumeroEmpleadoSis").value = ""
                document.getElementById("inputResponsableSis").value = ""
                document.getElementById("inputRecursosSis").value = ""
                if(arr.length > 0){
                    arr[0].proyectoFactible == 1 ? document.getElementById("proyectoSi").checked = true:document.getElementById("proyectoNo").checked = true
                    document.getElementById("inputRazonSis").value = arr[0].razon
                    document.getElementById("inputTicketSis").value = arr[0].ticketAsignado
                    document.getElementById("proyectoJuntas").value = arr[0].duracionProyecto
                    document.getElementById("inputInicioSis").value = arr[0].inicio.date.substring(0,10)
                    document.getElementById("inputFinSis").value = arr[0].fin.date.substring(0,10)
                    document.getElementById("inputNumeroEmpleadoSis").value = arr[0].numeroEmpleado
                    document.getElementById("inputResponsableSis").value = arr[0].responsable
                    document.getElementById("inputRecursosSis").value = arr[0].recursosRequeridos
            
                    document.getElementById("inputFinSis").disabled = false
                    document.getElementById("selectDia").disabled = false
                }
            },
            error: function (error){
                console.log(error)
            }
        })
    }
    
    function getDataPlanMNJ(folio){
        query = "select * from [dbo].[SCDSIS_Solicitud_plan_trabajo_mst] where folio = '"+folio+"'"
        $.ajax({
            type: "GET",
            url: "models/consultaDatos.php",
            data: {
                query: query,
            },
            success: function (data) {
                var arr = JSON.parse(data)
                document.getElementById("inputRazonSis").readOnly = true
                document.getElementById("inputRazonSis").readOnly = true
                document.getElementById("inputTicketSis").readOnly = true
                document.getElementById("inputInicioSis").readOnly = true
                document.getElementById("inputResponsableSis").readOnly = true
                document.getElementById("inputNumeroEmpleadoSis").readOnly = true
                document.getElementById("inputRecursosSis").readOnly = true
                document.getElementById("proyectoSi").disabled = true
                document.getElementById("proyectoNo").disabled = true
                document.getElementById("btnProyectos").disabled = true
                if(arr.length > 0){
                    const factible = arr[0].proyectoFactible == 1 ? "Si" : "No"
                    document.getElementsByName("proyectoFactible").value = factible
                    document.getElementById("inputRazonSis").value = arr[0].razon
                    document.getElementById("inputTicketSis").value = arr[0].ticketAsignado
                    document.getElementById("proyectoJuntas").value = arr[0].duracionProyecto
                    document.getElementById("inputInicioSis").value = arr[0].inicio.date.substring(0, 10)
                    document.getElementById("inputFinSis").value = arr[0].fin.date.substring(0, 10)
                    document.getElementById("inputResponsableSis").value = arr[0].responsable
                    document.getElementById("inputNumeroEmpleadoSis").value = arr[0].numeroEmpleado
                    
                    document.getElementById("inputRecursosSis").value = arr[0].recursosRequeridos
                }
            },
            error: function (error){
                console.log(error)
            }
        })
    }
    
    function getDataPlanJuntas(folio){
        Swal.fire({
            title: 'Buscando juntas programadas',
            html: 'Por favor espere mientras un momento.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        document.getElementById("tbodydivJuntas").innerHTML = ""
        query = "select * from [dbo].[SCDSIS_Solicitud_plan_trabajo_juntas_programadas] where folio = '"+folio+"'"
        $.ajax({
            type: "GET",
            url: "models/consultaDatos.php",
            data: {
                query: query,
            },
            success: function (data) {
                Swal.close()
                var arr = JSON.parse(data)
                if(arr.length > 0){
                    for(i of arr){
                        const tr = document.createElement("tr")
    
                        const fecha = document.createElement("td")
                        fecha.innerHTML = i.fecha.date.substring(0, 10)
                        tr.appendChild(fecha)
    
                        document.getElementById("tbodydivJuntas").appendChild(tr)
                    }
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

    function checkEmptys(){
        var isValid = true

        if(factibleGlobal === "Si"){
            const inputIds = [
                "inputTicketSis",
                "inputDuracionSis",
                "inputInicioSis",
                "inputFinSis",
                "inputNumeroEmpleadoSis",
                "inputResponsableSis",
                "inputRecursosSis",
                "selectDia"
            ]

            inputIds.forEach(id => {
                const input = document.getElementById(id)
                if (input && input.value.trim() === "") {
                    isValid = false
                    input.style.border = "2px solid red"
                } else if (input) {
                    input.style.border = ""
                }
            })

            if (!isValid) {
                Swal.fire({
                    icon: "error",
                    title: "¡Error!",
                    text: "Por favor, complete todos los campos obligatorios.",
                });
                return false;
            }
            btnAutorizar()
        }else if(factibleGlobal === "No"){
            const inputIdsNuevo = [
                "inputRazonSis",
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

            if (!isValid) {
                Swal.fire({
                    icon: "error",
                    title: "¡Error!",
                    text: "Por favor, complete todos los campos obligatorios.",
                });
                return false;
            }
            btnNoFactible()
        }    
    }
    
    function checkStatusFolio(folio,pagina){
        document.getElementById("botonesDiv").innerHTML = ""
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
                         
                    }else if(arr[0].autorizado == 1 && pagina == "MNJ"){
                        Swal.fire({
                            icon: "warning",
                            title: "¡Alerta!",
                            text: "El folio: "+document.getElementById("inputFolio").value+" Aún no ha sido autorizado por el encargado del departamento de Sistemas. ¿Desea verlo de todas formas?",
                            showCancelButton: true,
                            confirmButtonText: "Si"
                        }).then((result) => {
                            if(result.isConfirmed){ 
                                getData(document.getElementById("inputFolio").value)
                                getDataPlanMNJ(document.getElementById("inputFolio").value)
                            }
                        })
                    }else if(arr[0].autorizado == 1 && pagina == "Sistemas"){
                        getData(document.getElementById("inputFolio").value)
                        getDataPlanSistemas(document.getElementById("inputFolio").value)
                        getDataPlanJuntas(document.getElementById("inputFolio").value)
                        getDataPlanProyectos(document.getElementById("inputFolio").value)
                        
                        const div = document.createElement("div")
                        div.className = "col-md-12"

                        const btn1 = document.createElement('input')
                        btn1.id = 'btnAutorizar'
                        btn1.className = 'col-md-5 btn btn-info'
                        btn1.type = 'submit'
                        btn1.value = 'Autorizar & Generar Plan de Trabajo'
                        btn1.onclick = function() { checkEmptys() }

                        const spacer = document.createElement('div');
                        spacer.className = 'col-md-2';

                        const btn2 = document.createElement('input')
                        btn2.id = 'btnRechazar'
                        btn2.className = 'col-md-5 btn btn-info'
                        btn2.type = 'submit'
                        btn2.value = 'Rechazar'
                        btn2.onclick = function() { openModal() }

                        div.appendChild(btn1)
                        div.appendChild(spacer)
                        div.appendChild(btn2)

                        document.getElementById("botonesDiv").appendChild(div)
                    }else if(arr[0].autorizado > 1 && pagina == "Sistemas"){
                        Swal.fire({
                            icon: "info",
                            title: "Aviso",
                            text: "El folio: "+document.getElementById("inputFolio").value+"  ya ha sido autorizado por el departamento de Sistemas. ¿Desea verlo de todas formas?",
                            showCancelButton: true,
                            confirmButtonText: "Si"
                        }).then((result) => {
                            if(result.isConfirmed){ 
                                getData(document.getElementById("inputFolio").value)
                                getDataPlanSistemas(document.getElementById("inputFolio").value)
                                getDataPlanJuntas(document.getElementById("inputFolio").value)
                                getDataPlanProyectos(document.getElementById("inputFolio").value)
                            }
                        })
                    }else if(arr[0].autorizado == 2 && pagina == "MNJ"){
                        getData(document.getElementById("inputFolio").value)
                        getDataPlanMNJ(document.getElementById("inputFolio").value)
                        getDataPlanJuntas(document.getElementById("inputFolio").value)
                        getDataPlanProyectos(document.getElementById("inputFolio").value)

                        const div = document.createElement("div")
                        div.className = "col-md-12"

                        const btn1 = document.createElement('input')
                        btn1.id = 'btnAutorizar'
                        btn1.className = 'col-md-3 btn btn-info'
                        btn1.type = 'submit'
                        btn1.value = 'Autorizar Plan de Trabajo'
                        btn1.onclick = function() { updateAutorizadoMNJ() }

                        const spacer = document.createElement('div');
                        spacer.className = 'col-md-1';

                        const spacer2 = document.createElement('div');
                        spacer2.className = 'col-md-2';

                        const btn2 = document.createElement('input')
                        btn2.id = 'btnRechazar'
                        btn2.className = 'col-md-3 btn btn-info'
                        btn2.type = 'submit'
                        btn2.value = 'Rechazar'
                        btn2.onclick = function() { openModal() }

                        const btn3 = document.createElement('input')
                        btn3.id = 'btnRechazar'
                        btn3.className = 'col-md-3 btn btn-info'
                        btn3.type = 'submit'
                        btn3.value = 'Solicitar Otro Plan'
                        btn3.onclick = function() { openModalCambioPlan() }

                        div.appendChild(btn1)
                        div.appendChild(spacer)
                        div.appendChild(btn3)
                        div.appendChild(spacer2)
                        div.appendChild(btn2)
                        
                        document.getElementById("botonesDiv").appendChild(div)
                    }else if(arr[0].autorizado > 2 && pagina == "MNJ"){
                        Swal.fire({
                            icon: "info",
                            title: "Aviso",
                            text: "El folio: "+document.getElementById("inputFolio").value+"  ya ha sido autorizado por MNJ Sistemas. ¿Desea verlo de todas formas?",
                            showCancelButton: true,
                            confirmButtonText: "Si"
                        }).then((result) => {
                            if(result.isConfirmed){ 
                                getData(document.getElementById("inputFolio").value)
                                getDataPlanMNJ(document.getElementById("inputFolio").value)
                                getDataPlanJuntas(document.getElementById("inputFolio").value)
                                getDataPlanProyectos(document.getElementById("inputFolio").value)
                            }
                        })
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

    async function btnAutorizar(){
        const folio = document.getElementById("inputFolio").value

        var proyectoFactible = document.querySelector('input[name="proyectoFactible"]:checked').value
        var ticket = document.getElementById("inputTicketSis").value
        var inicio = document.getElementById("inputInicioSis").value
        var fin = document.getElementById("inputFinSis").value
        var responsable = document.getElementById("inputResponsableSis").value
        var numeroEmpleado = document.getElementById("inputNumeroEmpleadoSis").value
        var recursos = document.getElementById("inputRecursosSis").value

        const nombreProyectos = document.querySelectorAll('input[name="textboxMotivo"]')
        const proyectos = Array.from(nombreProyectos).map(input => input.value)

        const duraciones = document.querySelectorAll('input[name="textboxMotivo2"]')
        const duracionesProyectos = Array.from(duraciones).map(input => input.value)

        const fechasTermino = document.querySelectorAll('input[name="fechaTermino[]"]') 
        const fechasTerminoProyectos = Array.from(fechasTermino).map(input => input.value)

        for(let i = 0; i < proyectos.length; i++){
            if(proyectos[i] == ""){
                Swal.fire({
                    icon: "warning",
                    title: "¡Alerta!",
                    text: "Falta el nombre del proyecto "+(i+1)+".",
                })
                return
            }
        if(duracionesProyectos[i] == ""){
                Swal.fire({
                    icon: "warning",
                    title: "¡Alerta!",
                    text: "Falta la duración del proyecto "+(i+1)+".",
                })
                return
        }
        if(fechasTerminoProyectos[i] == ""){
                Swal.fire({
                    icon: "warning",
                    title: "¡Alerta!",
                    text: "Falta la fecha del proyecto "+(i+1)+".",
                })
                return
            }
        }

        Swal.fire({
            title: 'Guardando información',
            html: 'Por favor espere mientras se guarda la información.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        await fillMeetings(folio)
        fillProjects(folio, proyectos, duracionesProyectos, fechasTerminoProyectos)

        query = "SP_SCDSIS_Plan_De_Trabajo '"+folio+"', '"+proyectoFactible+"', '', '"+ticket+"', '"+result.length+" Semanas', '"+inicio+"', '"+
            fin+"', '"+responsable+"', '"+numeroEmpleado+"', '1', '"+recursos+"',''"
        $.ajax({
            type: "GET",
            url: "models/executaQuery.php",
            data: {
                query: query,
            },
            success: function (data) {
                var arr = JSON.parse(data)
                if(arr == 1){
                    mailAuthorizeSistemas()
                    Swal.close()
                }else{
                    Swal.fire({
                        icon: "error",
                        title: "¡Error!",
                        text: "Ocurrió un error al autorizar el folio.",
                    })
                }
            }
        })
    }

    function fillProjects(folio, proyectos, duracionesProyectos, fechasTerminoProyectos){
        for(let i = 0; i < proyectos.length; i++){
            query = "INSERT INTO SCDSIS_Solicitud_plan_trabajo_proyectos_previos VALUES ('"+folio+"', '"+
                proyectos[i]+"', '"+duracionesProyectos[i]+"', '"+fechasTerminoProyectos[i]+"')"
            $.ajax({
                type: "GET",
                url: "models/executaQuery.php",
                data: {
                    query: query,
                },
            })
        }
    }

    async function fillMeetings(folio){
        function ajaxPromise(options) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    ...options,
                    success: resolve,
                    error: reject
                })
            })
        }

        let query2 = `DELETE FROM [dbo].[SCDSIS_Solicitud_plan_trabajo_juntas_programadas] WHERE folio = '${folio}';`
        await ajaxPromise({
            type: "GET",
            url: "models/executaQuery.php",
            data:{query:query2}
        })
            
        for(let i = 0; i < result.length ;i++){
            let query = `INSERT INTO [dbo].[SCDSIS_Solicitud_plan_trabajo_juntas_programadas] VALUES ('${folio}','${result[i]}','','','','','','','');`
            await ajaxPromise({
                type: "GET",
                url: "models/executaQuery.php",
                data: { query: query }
            })
        }
    }
})

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

function btnSolicitarCambioPlanTrabajo(motivo){
    let query = "UPDATE [dbo].[SCDSIS_Solicitud_registro_mst] SET autorizado = 1 WHERE folio = '"+document.getElementById("inputFolio").value+"'"
    $.ajax({
        type: "GET",
        url: "models/executaQuery.php",
        data: {query:query},
        success: function(data){  }
    })

    const folio = document.getElementById("inputFolio").value
    const destino = "practicante.sistemas@sewsus.com.mx"//"sergio.barron@sewsus.com.mx"//cambiar por encargado de Sistemas*
    const autor = document.getElementById("inputNombre").value
    const fecha = document.getElementById("inputFecha").value

    $.ajax({
        type: "POST",
        url: "enviar_correo.php",
        data: {
            to: destino,
            subject: "REVISION DE PLAN DE TRABAJO para el folio: "+folio,
            body: "Se ha solicitado una revisión para la Solicitud de cambio o desarrollo de Sistema con folio: "+folio+", realizado por: "+autor+", con fecha: "+fecha+"."+
            "Con el motivo de: "+motivo+". Ingrese al siguiente link para revisarlo: http://smatrsaulocal:8080/Apps/wisa/solicitud_cambio_sistema_planTrabajo.php?folio="+folio+"&id=Sistemas"
        },
        success: function(response) {
            console.log("Email sent successfully, "+response)
            Swal.fire({
                icon: "success",
                title: "¡Éxito!",
                text: "Se solicitó un cambio de plan para el folio: "+document.getElementById("inputFolio").value+" correctamente.",
            }).then((result) => {
                if(result.isConfirmed){
                    window.location.href = "solicitud_cambio_sistema_planTrabajo.php"
                }
            })
        },
        error: function(error) {
            console.error("Error sending email", error)
        }
    })
}

function getCurrentDateTime(){
    const now = new Date()
    const year = now.getFullYear()
    const mm = String(now.getMonth() + 1).padStart(2, '0')
    const dd = String(now.getDate()).padStart(2, '0')
    const hh = String(now.getHours()).padStart(2, '0')
    const min = String(now.getMinutes()).padStart(2, '0')
    const ss = String(now.getSeconds()).padStart(2, '0')
    return `${year}-${mm}-${dd} ${hh}:${min}:${ss}`
}

function btnNoFactible(){
    const folio = document.getElementById("inputFolio").value

    var proyectoFactible = document.querySelector('input[name="proyectoFactible"]:checked').value
    var razon = document.getElementById("inputRazonSis").value
    
    if(razon == ""){
        Swal.fire({
            icon: "warning",
            title: "¡Alerta!",
            text: "Escriba una razón de porque NO es factible el proyecto"
        })
        return
    }

    let fecha = getCurrentDateTime()

    let query = "SP_SCDSIS_Plan_De_Trabajo '"+folio+"', '"+proyectoFactible+"', '"+razon+"', 0, '-', '"+fecha+"', '"+fecha+"', '-', 0, 0, '-', 'Barron Bolivar, Sergio Antonio'"
    $.ajax({
        type: "GET",
        url: "models/executaQuery.php",
        data: { query: query },
        success: function (data) {
            var arr = JSON.parse(data)
            if(arr[0].Error == 0){ 
                Swal.fire({
                    icon: "success",
                    title: "¡Éxito!",
                    text: "La solicitud fue guardada como NO factible correctamente"
                }).then((result) => {
                    if(result.isConfirmed){ window.location.href = "solicitud_cambio_sistema_planTrabajo.php" }
                })
            }else{
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Ocurrió un error al intentar guardar como NO factible"
                }).then((result) => {
                    if(result.isConfirmed){ window.location.href = "solicitud_cambio_sistema_planTrabajo.php" }
                })
            }
        }
    })
}

function btnAddProjects(){
    const proyectosDiv = document.getElementById("proyectos")

    const rowDiv = document.createElement("div")
    rowDiv.style.display = "flex"
    rowDiv.style.gap = "8px"
    rowDiv.style.marginBottom = "8px"
    rowDiv.className = "project-row"

    var textbox = document.createElement("input")
    textbox.setAttribute("type","text")
    textbox.setAttribute("name","textboxMotivo")
    textbox.setAttribute("class","form-horizontal form-material")
    textbox.setAttribute("placeholder","Nombre del proyecto")
    textbox.style.textAlign = "center"

    var textbox2 = document.createElement("input")
    textbox2.setAttribute("type","text")
    textbox2.setAttribute("name","textboxMotivo2")
    textbox2.setAttribute("class","form-horizontal form-material")
    textbox2.setAttribute("placeholder","Duración")
    textbox2.style.textAlign = "center"

    const input = document.createElement("input")
    input.type = "date"
    input.name = "fechaTermino[]"

    const today = new Date().toISOString().split("T")[0]
    input.setAttribute("max", today)

    const removeButton = document.createElement("button")
    removeButton.textContent = "Quitar"
    removeButton.type = "button"
    removeButton.className = "btn btn-danger btn-sm"
    removeButton.addEventListener("click", function() {
        proyectosDiv.removeChild(rowDiv)
    });

    rowDiv.appendChild(textbox)
    rowDiv.appendChild(textbox2)
    rowDiv.appendChild(input)
    rowDiv.appendChild(removeButton)
    proyectosDiv.appendChild(rowDiv)
}

function btnRechazarSistemas(){
    const folio = document.getElementById("inputFolio").value
    var motivo = document.getElementById("inputRechazoMotivo").value
    var usuario = "Barron Bolivar, Sergio Antonio"

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
                        window.location.href = "solicitud_cambio_sistema_planTrabajo.php"
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

function btnRechazarSistemasMNJ(){
    const folio = document.getElementById("inputFolio").value
    var motivo = document.getElementById("inputRechazoMotivo").value

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
    query = "SP_SCDSIS_Solicitud_registro_rechazo '"+folio+"','"+motivo+"','"+MNJsistemas+"','Rechazo',"+(-1)+";"
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
                        window.location.href = "solicitud_cambio_sistema_planTrabajo.php"
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

function updateAutorizadoMNJ(){
    let query = "UPDATE [dbo].[SCDSIS_Solicitud_registro_mst] SET autorizado = 3 WHERE folio = '"+document.getElementById("inputFolio").value+"'"
    $.ajax({
        type: "GET",
        url: "models/executaQuery.php",
        data: {query:query},
        success: function(data){
            let arr = JSON.parse(data)
            if(arr == 1){mailAuthorizeSistemasMNJ()}
            else{
                Swal.fire({
                    icon: "error",
                    title: "¡Error!",
                    text: "No se pudo autorizar el folio.",
                })
            }
        }
    })
}

function mailAuthorizeSistemasMNJ(){
    const folio = document.getElementById("inputFolio").value
    const destino = "practicante.sistemas@sewsus.com.mx"//"sergio.barron@sewsus.com.mx"//cambiar por encargado de Sistemas*
    const autor = document.getElementById("inputNombre").value
    const fecha = document.getElementById("inputFecha").value

    const linkConsulta = "http://smatrsaulocal:8080/Apps/wisa/solicitud_cambio_sistema_consultaGeneral.php?folio="+folio+""
    $.ajax({
        type: "POST",
        url: "enviar_correo.php",
        data: {
            to: destino,
            subject: "Solicitud de cambio o desarrollo de Sistema, "+folio,
            body: "Se ha completado una Solicitud de cambio o desarrollo de Sistema con folio: "+folio+
            ", realizado por: "+autor+", con fecha: "+fecha+
            ". Ingrese al siguiente link para revisarlo: "+linkConsulta+""
        },
        success: function(response) {
            console.log("Email sent successfully, "+response)
            Swal.fire({
                icon: "success",
                title: "¡Éxito!",
                text: "El folio: "+document.getElementById("inputFolio").value+" fue autorizado correctamente.",
            }).then((result) => {
                if(result.isConfirmed){
                    window.location.href = "solicitud_cambio_sistema_planTrabajo.php"
                }
            })
        },
        error: function(error) {
            console.error("Error sending email", error)
        }
    })
}

function mailAuthorizeSistemas(){
    const folio = document.getElementById("inputFolio").value
    const destino = "practicante.sistemas@sewsus.com.mx"//cambiar por encargado de Sistemas
    const autor = document.getElementById("inputNombre").value
    const fecha = document.getElementById("inputFecha").value
    const link = "http://smatrsaulocal:8080/Apps/wisa/solicitud_cambio_sistema_planTrabajo.php?folio="+folio+"&id=MNJ"

    $.ajax({
        type: "POST",
        url: "enviar_correo.php",
        data: {
            to: destino,
            subject: "Solicitud de cambio o desarrollo de Sistema, "+folio,
            body: "Se ha aprobado por Sistemas una Solicitud de cambio o desarrollo de Sistema con folio: "+folio+
            ", realizado por: "+autor+", con fecha: "+fecha+
            ". Ingrese al siguiente link para revisarlo: "+link
        },
        success: function(response) {
            console.log("Email sent successfully")
            Swal.fire({
                icon: "success",
                title: "¡Éxito!",
                text: "El folio: "+document.getElementById("inputFolio").value+" fue autorizado correctamente.",
            }).then((result) => {
                if(result.isConfirmed){
                    window.location.href = "solicitud_cambio_sistema_planTrabajo.php"
                }
            })
        },
        error: function(error) {
            console.error("Error sending email", error)
        }
    })
}

function checkMNJSistemas(){
    var query = "SELECT nombreEncargado FROM SCDSIS_Encargados_departamentos WHERE departamento = 'Sistemas' and planta = 'SAU'"
    $.ajax({
        type: "GET",
        url: "models/consultaDatos.php",
        data: {
            query: query,
        },
        success: function (data) {
            var arr = JSON.parse(data)
            if (arr.length > 0){
                MNJsistemas = arr[0].nombreEncargado
            }
        }
    })
}

//Modal

const modal = document.getElementById('myModal');
const modalCambio = document.getElementById('myModal2');

const closeModalButton = document.getElementById('closeModal');
const closeModalButton2 = document.getElementById('closeModal2');


function openModal() {
    modal.style.display = 'block';
}

function openModalCambioPlan(){
    modalCambio.style.display = 'block';
}

function closeModal() {
    modal.style.display = 'none';
    modalCambio.style.display = 'none';
}

closeModalButton.addEventListener('click', closeModal);
closeModalButton2.addEventListener('click', closeModal);

window.addEventListener('click', function(event) {
    if (event.target === modal) {
        closeModal();
    }
});