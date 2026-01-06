document.addEventListener("DOMContentLoaded", function() {
    var dict_Estatus = {
        10: "Solicitud Rechazada",
        0: "Solicitud Nueva",
        1: "Autorizado por el Departamento",
        2: "Autorizado por Desarrollo",
        3: "Autorización Completa",
        4: "Aprobada Completamente",
        5: "Liberado por Requisitor"
    }

    document.getElementById('btnGoTop').onclick = function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    
    document.getElementById('btnGoBottom').onclick = function() {
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
    }
    
    document.getElementById("selectOption").addEventListener("change", function(){
        document.getElementById("divCampos").innerHTML = ""
        document.getElementById("tbodydivFolios").innerHTML = ""
    
        if(this.value == "1"){ 
            searchAllFolios()
        }else if(this.value == "2"){ 
            const inputFolio = document.createElement("input")
            inputFolio.setAttribute("type", "text")
            inputFolio.setAttribute("id", "inputFolio")
            inputFolio.setAttribute("placeholder", "Escriba el nombre del Folio")
            inputFolio.setAttribute("class", "form-control")
    
            inputFolio.addEventListener("change", function(){
                document.getElementById("tbodydivFolios").innerHTML = ""
    
                if(this.value == ""){ return }
                var query = "SELECT * FROM [dbo].[SCDSIS_Solicitud_registro_mst] WHERE folio like '"+this.value+"%' ORDER BY fecha DESC"
    
                $.ajax({
                    type: "GET",
                    url: "models/consultaDatos.php",
                    data: {
                        query: query,
                    },
                    success: function (data) {
                        var jsonData = JSON.parse(data)
                        if(jsonData.length > 0){
                            fillTable(jsonData)
                        }else { 
                            Swal.fire({
                                icon: "warning",
                                title: "¡Sin Resultados!"
                            })
                        }
                    }
                })
            })
    
            document.getElementById("divCampos").appendChild(inputFolio)
        }else if(this.value == "3"){ 
            const inputName = document.createElement("input")
            inputName.setAttribute("type", "text")
            inputName.setAttribute("id", "inputName")
            inputName.setAttribute("placeholder", "Escriba el nombre del Sistema")
            inputName.setAttribute("class", "form-control")
    
            inputName.addEventListener("change", function(){
                document.getElementById("tbodydivFolios").innerHTML = ""
    
                if(this.value == ""){ return }
                var query = "SELECT * FROM [dbo].[SCDSIS_Solicitud_registro_mst] WHERE nombreSistema like '"+this.value+"%' ORDER BY fecha DESC"
    
                $.ajax({
                    type: "GET",
                    url: "models/consultaDatos.php",
                    data: {
                        query: query,
                    },
                    success: function (data) {
                        var jsonData = JSON.parse(data)
                        if(jsonData.length > 0){
                            fillTable(jsonData)
                        }else { 
                            Swal.fire({
                                icon: "warning",
                                title: "¡Sin Resultados!"
                            })
                        }
                    }
                })
            })
    
            document.getElementById("divCampos").appendChild(inputName)
        }else if(this.value == "4"){
            const selectDepartment = document.createElement("select")
            selectDepartment.setAttribute("type", "text")
            selectDepartment.setAttribute("id", "inputDepartment")
            selectDepartment.setAttribute("class", "form-control")
    
            selectDepartment.addEventListener("change", function(){
                document.getElementById("tbodydivFolios").innerHTML = ""
    
                if(this.value == ""){ return }
                var query = "SELECT * FROM [dbo].[SCDSIS_Solicitud_registro_mst] WHERE departamento = '"+this.value+"' ORDER BY fecha DESC"
    
                $.ajax({
                    type: "GET",
                    url: "models/consultaDatos.php",
                    data: {
                        query: query,
                    },
                    success: function (data) {
                        var jsonData = JSON.parse(data)
                        if(jsonData.length > 0){
                            fillTable(jsonData)
                        }else { 
                            Swal.fire({
                                icon: "warning",
                                title: "¡Sin Resultados!"
                            })
                        }
                    }
                })
            })
    
            document.getElementById("divCampos").appendChild(selectDepartment)
    
            searchDepartments()
        }else if(this.value == "5"){
            const rangoText = document.createElement("label")
            rangoText.innerHTML = "Fechas:" 
            rangoText.id = "labelFechas"

            const rext = document.createElement("label")
            rext.innerHTML = " - " 
            rext.id = "label-"

            const inputDate1 = document.createElement("input")
            inputDate1.setAttribute("type", "date")
            inputDate1.setAttribute("id", "inputDate1")
            inputDate1.setAttribute("class", "form-control")
    
            inputDate1.addEventListener("change", function(){
                inputDate2.value = ""
                inputDate2.disabled = false
            })
    
            const inputDate2 = document.createElement("input")
            inputDate2.setAttribute("type", "date")
            inputDate2.setAttribute("id", "inputDate2")
            inputDate2.setAttribute("class", "form-control")
            inputDate2.disabled = true
    
            inputDate2.addEventListener("change", function(){
                document.getElementById("tbodydivFolios").innerHTML = ""
    
                var query = "SELECT * FROM [dbo].[SCDSIS_Solicitud_registro_mst] WHERE fecha BETWEEN '"+inputDate1.value+"' AND '"+this.value+"' ORDER BY fecha DESC"
    
                $.ajax({
                    type: "GET",
                    url: "models/consultaDatos.php",
                    data: {
                        query: query,
                    },
                    success: function (data) {
                        var jsonData = JSON.parse(data)
                        if(jsonData.length > 0){
                            fillTable(jsonData)
                        }else { 
                            Swal.fire({
                                icon: "warning",
                                title: "¡Sin Resultados!"
                            })
                        }
                    }
                })
            })
    
            document.getElementById("divCampos").appendChild(rangoText)
            document.getElementById("divCampos").appendChild(inputDate1)
            document.getElementById("divCampos").appendChild(rext)
            document.getElementById("divCampos").appendChild(inputDate2)
        }else if(this.value == "6"){
            const selectTrabajo = document.createElement("select")
            selectTrabajo.setAttribute("type", "text")
            selectTrabajo.setAttribute("id", "inputTrabajo")
            selectTrabajo.setAttribute("class", "form-control")
            selectTrabajo.innerHTML = `<option value="0">Seleccione una Opción</option> <option value="Nuevo">Nuevo</option> <option value="Cambio">Cambio</option>`
    
            selectTrabajo.addEventListener("change", function(){
                document.getElementById("tbodydivFolios").innerHTML = ""
    
                if(this.value == "0"){ return }
                var query = "SELECT * FROM [dbo].[SCDSIS_Solicitud_registro_mst] WHERE tipoTrabajo = '"+this.value+"' ORDER BY fecha DESC"
    
                $.ajax({
                    type: "GET",
                    url: "models/consultaDatos.php",
                    data: {
                        query: query,
                    },
                    success: function (data) {
                        var jsonData = JSON.parse(data)
                        if(jsonData.length > 0){
                            fillTable(jsonData)
                        }else { 
                            Swal.fire({
                                icon: "warning",
                                title: "¡Sin Resultados!"
                            })
                        }
                    }
                })
            })
    
            document.getElementById("divCampos").appendChild(selectTrabajo)
        }else if(this.value == "7"){
            const selectEstatus = document.createElement("select")
            selectEstatus.setAttribute("type", "text")
            selectEstatus.setAttribute("id", "inputEstatus")
            selectEstatus.setAttribute("class", "form-control")
            selectEstatus.innerHTML = 
            `<option value="">Seleccione una Opción</option>
             <option value="0">Solicitud Nueva</option> 
             <option value="1">Autorizado por el Departamento</option>
             <option value="2">Autorizado por Desarrollo</option>
             <option value="3">Autorización Completa</option>
             <option value="4">Aprobada Completamente</option>
             <option value="5">Liberado por Requisitor</option>
             <option value="-1">Solicitud Rechazada</option>
            `
            selectEstatus.addEventListener("change", function(){
                document.getElementById("tbodydivFolios").innerHTML = ""
    
                if(this.value == ""){ return }
                var query = "SELECT * FROM [dbo].[SCDSIS_Solicitud_registro_mst] WHERE autorizado = '"+this.value+"' ORDER BY fecha DESC"
    
                $.ajax({
                    type: "GET",
                    url: "models/consultaDatos.php",
                    data: {
                        query: query,
                    },
                    success: function (data) {
                        var jsonData = JSON.parse(data)
                        if(jsonData.length > 0){
                            fillTable(jsonData)
                        }else { 
                            Swal.fire({
                                icon: "warning",
                                title: "¡Sin Resultados!"
                            })
                        }
                    }
                })
            })
    
            document.getElementById("divCampos").appendChild(selectEstatus)
        }else if(this.value == "8"){
            const selectUrgencia = document.createElement("select")
            selectUrgencia.setAttribute("type", "text")
            selectUrgencia.setAttribute("id", "inputUrgencia")
            selectUrgencia.setAttribute("class", "form-control")
            selectUrgencia.innerHTML = `<option value="">Seleccione una Opción</option> <option value="1">Prioridad</option> <option value="2">Normal</option>`
    
            selectUrgencia.addEventListener("change", function(){
                document.getElementById("tbodydivFolios").innerHTML = ""
    
                if(this.value == ""){ return }
                var query = ""
                if(this.value == 1){ query = "SELECT * FROM [dbo].[SCDSIS_Solicitud_registro_mst] WHERE urgente = '"+this.value+"' ORDER BY fecha DESC" }
                else{ query = "SELECT * FROM [dbo].[SCDSIS_Solicitud_registro_mst] WHERE urgente < 1 ORDER BY fecha DESC" }
    
                $.ajax({
                    type: "GET",
                    url: "models/consultaDatos.php",
                    data: {
                        query: query,
                    },
                    success: function (data) {
                        var jsonData = JSON.parse(data)
                        if(jsonData.length > 0){
                            fillTable(jsonData)
                        }else { 
                            Swal.fire({
                                icon: "warning",
                                title: "¡Sin Resultados!"
                            })
                        }
                    }
                })
            })
            document.getElementById("divCampos").appendChild(selectUrgencia)
        }else if(this.value == 9){
            const inputReloj = document.createElement("input")
            inputReloj.setAttribute("type", "number")
            inputReloj.setAttribute("id", "inputFolio")
            inputReloj.setAttribute("placeholder", "Escriba el número de Reloj")
            inputReloj.setAttribute("class", "form-control")
    
            inputReloj.addEventListener("change", function(){
                if(this.value == ""){ return }
                var query = "SELECT * FROM [dbo].[SCDSIS_Solicitud_registro_mst] WHERE numeroSolicitante = "+this.value+" ORDER BY fecha DESC"

                $.ajax({
                    type: "GET",
                    url: "models/consultaDatos.php",
                    data: {
                        query: query,
                    },
                    success: function (data) {
                        var jsonData = JSON.parse(data)
                        if(jsonData.length > 0){
                            fillTable(jsonData)
                        }else { 
                            Swal.fire({
                                icon: "warning",
                                title: "¡Sin Resultados!"
                            })
                        }
                    }
                })
            })
    
            document.getElementById("divCampos").appendChild(inputReloj)
        }
    })
    
    function searchAllFolios(){
        document.getElementById("tbodydivFolios").innerHTML = ""
    
        var query = "SELECT * FROM [dbo].[SCDSIS_Solicitud_registro_mst] ORDER BY fecha DESC"
    
        $.ajax({
            type: "GET",
            url: "models/consultaDatos.php",
            data: {
                query: query,
            },
            success: function (data) {
                var jsonData = JSON.parse(data)
                if(jsonData.length > 0){
                    fillTable(jsonData)
                }
            }
        })
    }
    
    function fillTable(jsonData){
        if(jsonData.length == 0){
            Swal.fire({
                icon: "warning",
                title: "¡Sin Resultados!"
            })
            return
        }
        for(i of jsonData){
            const row = document.createElement("tr")
    
            const folio = document.createElement("td")
            folio.innerHTML = i.folio
            row.appendChild(folio)
    
            const date = document.createElement("td")
            date.innerHTML = i.fecha.date.substring(0, 10)
            row.appendChild(date)
    
            const departament = document.createElement("td")
            departament.innerHTML = i.departamento
            row.appendChild(departament)
    
            const solicitudType = document.createElement("td")
            solicitudType.innerHTML = i.tipoSolicitud
            row.appendChild(solicitudType)
    
            const trabajoType = document.createElement("td")
            trabajoType.innerHTML = i.tipoTrabajo
            row.appendChild(trabajoType)
    
            const systemName = document.createElement("td")
            systemName.innerHTML = i.nombreSistema
            row.appendChild(systemName)
    
            var estatus = i.autorizado == -1 ? "Rechazado" : dict_Estatus[i.autorizado]
            const status = document.createElement("td")
            status.innerHTML = estatus
            row.appendChild(status)
    
            var urgencia = i.urgente == 1 ? "Prioridad" : "Normal"
            const urgency = document.createElement("td")
            urgency.innerHTML = urgencia
            row.appendChild(urgency)
    
            row.addEventListener("dblclick", function(){
                window.open("solicitud_cambio_sistema_consultaGeneral.php?folio="+folio.innerHTML+"", "_blank")
            })
            document.getElementById("tbodydivFolios").appendChild(row)
        }
    }
    
    function searchDepartments(){
        var select = document.getElementById("inputDepartment")
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
})