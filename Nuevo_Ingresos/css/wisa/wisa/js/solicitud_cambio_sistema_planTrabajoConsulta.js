document.addEventListener("DOMContentLoaded", function() {
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
            var query = `SELECT A.folio, A.inicio, A.fin, A.duracionProyecto, A.responsable, B.departamento, B.nombreSistema, B.urgente 
            FROM [dbo].[SCDSIS_Solicitud_plan_trabajo_mst] A
            INNER JOIN [dbo].[SCDSIS_Solicitud_registro_mst] B ON A.folio = B.folio
            WHERE B.autorizado = 4
            ORDER BY folio`
    
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
        }else if(this.value == "3"){ 
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
    
                var query = `SELECT A.folio, A.inicio, A.fin, A.duracionProyecto, A.responsable, B.departamento, B.nombreSistema, B.urgente 
                FROM [dbo].[SCDSIS_Solicitud_plan_trabajo_mst] A
                INNER JOIN [dbo].[SCDSIS_Solicitud_registro_mst] B ON A.folio = B.folio
                WHERE B.autorizado = 4 AND B.fecha BETWEEN '${document.getElementById('inputDate1').value}' AND '${this.value}'
                ORDER BY folio DESC`
    
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
        }else if(this.value == "4"){
            const selectDepartment = document.createElement("select")
            selectDepartment.setAttribute("type", "text")
            selectDepartment.setAttribute("id", "inputDepartment")
            selectDepartment.setAttribute("class", "form-control")
    
            selectDepartment.addEventListener("change", function(){
                document.getElementById("tbodydivFolios").innerHTML = ""
    
                if(this.value == ""){ return }
                var query = `SELECT A.folio, A.inicio, A.fin, A.duracionProyecto, A.responsable, B.departamento, B.nombreSistema, B.urgente 
                FROM [dbo].[SCDSIS_Solicitud_plan_trabajo_mst] A
                INNER JOIN [dbo].[SCDSIS_Solicitud_registro_mst] B ON A.folio = B.folio
                WHERE B.autorizado = 4 AND B.departamento = '${this.value}'
                ORDER BY folio DESC`
    
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
            var query = `SELECT A.folio, A.inicio, A.fin, A.duracionProyecto, A.responsable, B.departamento, B.nombreSistema, B.urgente 
            FROM [dbo].[SCDSIS_Solicitud_plan_trabajo_mst] A
            INNER JOIN [dbo].[SCDSIS_Solicitud_registro_mst] B ON A.folio = B.folio
            WHERE B.autorizado = 4 AND B.urgente = 1
            ORDER BY folio DESC`
    
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
        }
    })
    
    function searchAllFolios(){
        document.getElementById("tbodydivFolios").innerHTML = ""
    
        var query = `SELECT A.folio, A.inicio, A.fin, A.duracionProyecto, A.responsable, B.departamento, B.nombreSistema, B.urgente 
        FROM [dbo].[SCDSIS_Solicitud_plan_trabajo_mst] A
        INNER JOIN [dbo].[SCDSIS_Solicitud_registro_mst] B ON A.folio = B.folio
        WHERE B.autorizado = 4
        ORDER BY fin DESC`
    
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
    
            const date1 = document.createElement("td")
            date1.innerHTML = i.inicio.date.substring(0, 10)
            row.appendChild(date1)

            const date2 = document.createElement("td")
            date2.innerHTML = i.fin.date.substring(0, 10)
            row.appendChild(date2)
    
            const duracion = document.createElement("td")
            duracion.innerHTML = i.duracionProyecto
            row.appendChild(duracion)
    
            const responsable = document.createElement("td")
            responsable.innerHTML = i.responsable
            row.appendChild(responsable)
    
            const departamento = document.createElement("td")
            departamento.innerHTML = i.departamento
            row.appendChild(departamento)
    
            const nombreSistema = document.createElement("td")
            nombreSistema.innerHTML = i.nombreSistema
            row.appendChild(nombreSistema)
    
            var urgencia = i.urgente == 1 ? "Prioridad" : "Normal"
            const urgency = document.createElement("td")
            urgency.innerHTML = urgencia
            row.appendChild(urgency)
    
            row.addEventListener("dblclick", function(){
                window.open("solicitud_cambio_sistema_planTrabajoReporte.php?folio="+folio.innerHTML+"", "_blank")
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