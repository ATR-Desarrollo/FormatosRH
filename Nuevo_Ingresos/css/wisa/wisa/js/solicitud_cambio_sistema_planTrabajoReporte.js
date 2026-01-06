document.addEventListener("DOMContentLoaded",function(){
    var metaCumplidaGlobal = 1

    const urlParams = new URLSearchParams(window.location.search)
    const folio = urlParams.get("folio")

    document.getElementById("inputFolio").value = folio
    document.getElementById("inputFolio").style.display = "none"

    if(folio == null){ return }

    makeTitle(folio)
    getJuntas(folio)

    document.getElementById('btnGoTop').onclick = function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    
    document.getElementById('btnGoBottom').onclick = function() {
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
    }

    document.getElementById("selectJunta").addEventListener("change", function(event){
        getDataJunta(this.value)
    })
    
    function getJuntas(folio){
        query = `SELECT fecha FROM [dbo].[SCDSIS_Solicitud_plan_trabajo_juntas_programadas] WHERE folio = '${folio}';`
        $.ajax({
            type: "GET",
            url: "models/consultaDatos.php",
            data: {
                query: query,
            },
            success: function (data) {
                var arr = JSON.parse(data)
                if(arr.length > 0){
                    const option = document.createElement("option")
                    option.value = ""
                    option.text = "Juntas programadas:"
                    document.getElementById("selectJunta").appendChild(option)
                    
                    for(let i = 0; i < arr.length; i++){
                        const option = document.createElement("option")
                        option.value = option.text = arr[i].fecha.date.substring(0, 10)
                        document.getElementById("selectJunta").appendChild(option)
                    }
                }else{
                    Swal.fire({
                        icon: "error",
                        title: "¡Error!",
                        text: "No se encontraron juntas para el folio: "+document.getElementById("inputFolio").value+"."
                    })
                }
            },
            error: function (error){
                console.log(error)
            }
        })
    }

    function getDataJunta(fecha){
        if(fecha == ""){ return }
        document.getElementById("reporteDiv").innerHTML = ""

        let query = `SELECT * FROM [dbo].[SCDSIS_Solicitud_plan_trabajo_juntas_programadas] WHERE folio = '${document.getElementById("inputFolio").value}' AND fecha = '${fecha}';`
        $.ajax({
            type: "GET",
            url: "models/consultaDatos.php",
            data: {
                query: query,
            },
            success: function (data) {
                var arr = JSON.parse(data)
                if(arr.length > 0){
                    const counterMeta1 = document.createElement("span")
                    counterMeta1.id = "counterMeta1"
                    counterMeta1.style.float = "right"
                    counterMeta1.style.fontSize = "12px"
                    counterMeta1.style.color = "#007bff"
                    counterMeta1.innerText = `0 / 200`

                    const counterMeta2 = document.createElement("span")
                    counterMeta2.id = "counterMeta2"
                    counterMeta2.style.float = "right"
                    counterMeta2.style.fontSize = "12px"
                    counterMeta2.style.color = "#007bff"
                    counterMeta2.innerText = `0 / 200`

                    const counterMeta3 = document.createElement("span")
                    counterMeta3.id = "counterMeta3"
                    counterMeta3.style.float = "right"
                    counterMeta3.style.fontSize = "12px"
                    counterMeta3.style.color = "#007bff"
                    counterMeta3.innerText = `0 / 250`

                    const counterMeta4 = document.createElement("span")
                    counterMeta4.id = "counterMeta4"
                    counterMeta4.style.float = "right"
                    counterMeta4.style.fontSize = "12px"
                    counterMeta4.style.color = "#007bff"
                    counterMeta4.innerText = `0 / 250`

                    const counterMeta5 = document.createElement("span")
                    counterMeta5.id = "counterMeta5"
                    counterMeta5.style.float = "right"
                    counterMeta5.style.fontSize = "12px"
                    counterMeta5.style.color = "#007bff"
                    counterMeta5.innerText = `0 / 250`

                    const counterMeta6 = document.createElement("span")
                    counterMeta6.id = "counterMeta6"
                    counterMeta6.style.float = "right"
                    counterMeta6.style.fontSize = "12px"
                    counterMeta6.style.color = "#007bff"
                    counterMeta6.innerText = `0 / 200`

                    const labelMeta = document.createElement("label")
                    labelMeta.innerHTML = "Meta:"
                    labelMeta.className = "form-label"
                    labelMeta.for = "textareaMetaID"

                    const textareaMeta = document.createElement("textarea")
                    textareaMeta.id = "textareaMetaID"
                    textareaMeta.className = "form-control"
                    textareaMeta.placeholder = "Meta"
                    textareaMeta.maxLength = 200
                    textareaMeta.style.height = "50px"
                    textareaMeta.autocomplete = "off"
                    textareaMeta.addEventListener("input", function() {
                        counterMeta1.innerText = `${this.value.length} / 200`
                    })

                    const salto = document.createElement("br")

                    const labelMetaCumplida = document.createElement("label")
                    labelMetaCumplida.innerHTML = "¿Meta cumplida?"
                    labelMetaCumplida.className = "form-label"

                    const labelMetaCumplidaSi = document.createElement("label")
                    labelMetaCumplidaSi.innerHTML = "Si"
                    labelMetaCumplida.className = "form-label"

                    const labelMetaCumplidaNo = document.createElement("label")
                    labelMetaCumplidaNo.innerHTML = "No"
                    labelMetaCumplidaNo.className = "form-label"

                    const radioMetaCumplidaSi = document.createElement("input")
                    radioMetaCumplidaSi.id = "radioMetaCumplidaSiID"
                    radioMetaCumplidaSi.type = "radio"
                    radioMetaCumplidaSi.name = "metaCumplida"
                    radioMetaCumplidaSi.value = "1"
                    radioMetaCumplidaSi.style.margin = "10px"
                    radioMetaCumplidaSi.checked = true
                    radioMetaCumplidaSi.addEventListener("change", function(){
                        metaCumplidaGlobal = 1

                        textareaIncumplimiento.disabled = true
                        textareaIncumplimiento.value = ""
                        
                        const inputIds = [
                            "textareaMetaID",
                            "textareaIncumplimientoID",
                            "textareaTareasCumplidasID",
                            "textareaProblemasPresentadosID",
                            "textareaPlanEjecucionID",
                            "textareaNotasAdicionalesID",
                        ]

                        inputIds.forEach(id => {
                            document.getElementById(id).style.border = ""
                        })

                    })

                    const radioMetaCumplidaNo = document.createElement("input")
                    radioMetaCumplidaNo.id = "radioMetaCumplidaNoID"
                    radioMetaCumplidaNo.type = "radio"
                    radioMetaCumplidaNo.name = "metaCumplida"
                    radioMetaCumplidaNo.value = "0"
                    radioMetaCumplidaNo.style.margin = "10px"
                    radioMetaCumplidaNo.addEventListener("change", function(){
                        metaCumplidaGlobal = 0 

                        textareaIncumplimiento.disabled = false

                        const inputIds = [
                            "textareaMetaID",
                            "textareaIncumplimientoID",
                            "textareaTareasCumplidasID",
                            "textareaProblemasPresentadosID",
                            "textareaPlanEjecucionID",
                            "textareaNotasAdicionalesID",
                        ]

                        inputIds.forEach(id => {
                            document.getElementById(id).style.border = ""
                        })
                    })
                    
                    const labelIncumplimiento = document.createElement("label")
                    labelIncumplimiento.innerHTML = "Incumplimiento:"
                    labelIncumplimiento.className = "form-label"
                    labelIncumplimiento.for = "textareaIncumplimientoID"

                    const textareaIncumplimiento = document.createElement("textarea")
                    textareaIncumplimiento.id = "textareaIncumplimientoID"
                    textareaIncumplimiento.className = "form-control"
                    textareaIncumplimiento.placeholder = "Motivo de incumplimiento (Solo escribir en caso de que la meta no se haya cumplido)"
                    textareaIncumplimiento.disabled = true
                    textareaIncumplimiento.style.height = "50px"
                    textareaIncumplimiento.maxLength = 200
                    textareaIncumplimiento.autocomplete = "off"
                    textareaIncumplimiento.addEventListener("input", function() {
                        counterMeta2.innerText = `${this.value.length} / 200`
                    })
                    
                    const labelTareas= document.createElement("label")
                    labelTareas.innerHTML = "Tareas cumplidas:"
                    labelTareas.className = "form-label"
                    labelTareas.for = "textareaTareasCumplidasID"

                    const textareaTareasCumplidas = document.createElement("textarea")
                    textareaTareasCumplidas.id = "textareaTareasCumplidasID"
                    textareaTareasCumplidas.className = "form-control"
                    textareaTareasCumplidas.placeholder = "Tareas cumplidas"
                    textareaTareasCumplidas.style.height = "80px"
                    textareaTareasCumplidas.maxLength = 250
                    textareaTareasCumplidas.autocomplete = "off"
                    textareaTareasCumplidas.addEventListener("input", function() {
                        counterMeta3.innerText = `${this.value.length} / 250`
                    })

                    const labelProblemas = document.createElement("label")
                    labelProblemas.innerHTML = "Problemas presentados:"
                    labelProblemas.className = "form-label"
                    labelProblemas.for = "textareaProblemasPresentadosID"

                    const textareaProblemasPresentados = document.createElement("textarea")
                    textareaProblemasPresentados.id = "textareaProblemasPresentadosID"
                    textareaProblemasPresentados.className = "form-control"
                    textareaProblemasPresentados.placeholder = "Problemas presentados"
                    textareaProblemasPresentados.style.height = "80px"
                    textareaProblemasPresentados.maxLength = 250
                    textareaProblemasPresentados.autocomplete = "off"
                    textareaProblemasPresentados.addEventListener("input", function() {
                        counterMeta4.innerText = `${this.value.length} / 250`
                    })

                    const labelPlan = document.createElement("label")
                    labelPlan.innerHTML = "Plan de Ejecución para la siguiente semana:"
                    labelPlan.className = "form-label"
                    labelPlan.for = "textareaPlanEjecucionID"

                    const textareaPlanEjecucion = document.createElement("textarea")
                    textareaPlanEjecucion.id = "textareaPlanEjecucionID"
                    textareaPlanEjecucion.className = "form-control"
                    textareaPlanEjecucion.placeholder = "Plan de Ejecución para la siguiente semana"
                    textareaPlanEjecucion.style.height = "80px"
                    textareaPlanEjecucion.maxLength = 250
                    textareaPlanEjecucion.autocomplete = "off"
                    textareaPlanEjecucion.addEventListener("input", function() {
                        counterMeta5.innerText = `${this.value.length} / 250`
                    })
                    
                    const labelNotas = document.createElement("label")
                    labelNotas.innerHTML = "Notas Adicionales:"
                    labelNotas.className = "form-label"
                    labelNotas.for = "textareaNotasAdicionalesID"

                    const textareaNotasAdicionales = document.createElement("textarea")
                    textareaNotasAdicionales.id = "textareaNotasAdicionalesID"
                    textareaNotasAdicionales.className = "form-control"
                    textareaNotasAdicionales.placeholder = "Notas Adicionales"
                    textareaNotasAdicionales.style.height = "50px"
                    textareaNotasAdicionales.maxLength = 200
                    textareaNotasAdicionales.autocomplete = "off"
                    textareaNotasAdicionales.addEventListener("input", function() {
                        counterMeta6.innerText = `${this.value.length} / 200`
                    })

                    document.getElementById("reporteDiv").appendChild(salto)
                    document.getElementById("reporteDiv").appendChild(salto.cloneNode())
                    document.getElementById("reporteDiv").appendChild(labelMeta)
                    document.getElementById("reporteDiv").appendChild(textareaMeta)
                    document.getElementById("reporteDiv").appendChild(counterMeta1)
                    document.getElementById("reporteDiv").appendChild(salto.cloneNode())
                    document.getElementById("reporteDiv").appendChild(salto.cloneNode())

                    document.getElementById("reporteDiv").appendChild(labelMetaCumplida)
                    document.getElementById("reporteDiv").appendChild(salto.cloneNode())

                    document.getElementById("reporteDiv").appendChild(labelMetaCumplidaSi)
                    document.getElementById("reporteDiv").appendChild(radioMetaCumplidaSi)
                    document.getElementById("reporteDiv").appendChild(labelMetaCumplidaNo)
                    document.getElementById("reporteDiv").appendChild(radioMetaCumplidaNo)
                    document.getElementById("reporteDiv").appendChild(salto.cloneNode())
                    document.getElementById("reporteDiv").appendChild(salto.cloneNode())

                    document.getElementById("reporteDiv").appendChild(labelIncumplimiento)
                    document.getElementById("reporteDiv").appendChild(textareaIncumplimiento)
                    document.getElementById("reporteDiv").appendChild(counterMeta2)
                    document.getElementById("reporteDiv").appendChild(salto.cloneNode())
                    document.getElementById("reporteDiv").appendChild(salto.cloneNode())

                    document.getElementById("reporteDiv").appendChild(labelTareas)
                    document.getElementById("reporteDiv").appendChild(textareaTareasCumplidas)
                    document.getElementById("reporteDiv").appendChild(counterMeta3)
                    document.getElementById("reporteDiv").appendChild(salto.cloneNode())
                    document.getElementById("reporteDiv").appendChild(salto.cloneNode())

                    document.getElementById("reporteDiv").appendChild(labelProblemas)
                    document.getElementById("reporteDiv").appendChild(textareaProblemasPresentados)
                    document.getElementById("reporteDiv").appendChild(counterMeta4)
                    document.getElementById("reporteDiv").appendChild(salto.cloneNode())
                    document.getElementById("reporteDiv").appendChild(salto.cloneNode())

                    document.getElementById("reporteDiv").appendChild(labelPlan)
                    document.getElementById("reporteDiv").appendChild(textareaPlanEjecucion)
                    document.getElementById("reporteDiv").appendChild(counterMeta5)
                    document.getElementById("reporteDiv").appendChild(salto.cloneNode())
                    document.getElementById("reporteDiv").appendChild(salto.cloneNode())

                    document.getElementById("reporteDiv").appendChild(labelNotas)
                    document.getElementById("reporteDiv").appendChild(textareaNotasAdicionales)
                    document.getElementById("reporteDiv").appendChild(counterMeta6)
                    document.getElementById("reporteDiv").appendChild(salto.cloneNode())
                    document.getElementById("reporteDiv").appendChild(salto.cloneNode())

                    if(arr[0].meta != ""){
                        counterMeta1.remove() 
                        counterMeta2.remove() 
                        counterMeta3.remove() 
                        counterMeta4.remove() 
                        counterMeta5.remove() 
                        counterMeta6.remove() 

                        textareaMeta.value = arr[0].meta
                        textareaMeta.disabled = true

                        arr[0].metaCumplida == 1 ? radioMetaCumplidaSi.checked = true : radioMetaCumplidaNo.checked = true
                        radioMetaCumplidaNo.disabled = true
                        radioMetaCumplidaSi.disabled = true

                        if(arr[0].incumplimientoRazon == null || arr[0].incumplimientoRazon == ""){ textareaIncumplimiento.value = "Ninguno." }
                        else{ textareaIncumplimiento.value = arr[0].incumplimientoRazon }
                        textareaIncumplimiento.disabled = true
                        
                        textareaTareasCumplidas.value = arr[0].tareasCumplidas
                        textareaTareasCumplidas.disabled = true

                        textareaProblemasPresentados.value = arr[0].problemasPresentados
                        textareaProblemasPresentados.disabled = true

                        textareaPlanEjecucion.value = arr[0].planEjecucionSigSemana
                        textareaPlanEjecucion.disabled = true

                        textareaNotasAdicionales.value = arr[0].notasAdicionales
                        textareaNotasAdicionales.disabled = true
                    }else{
                        const div = document.createElement("div")
                        div.className = "col-md-5"

                        const btnGuardar = document.createElement("button")
                        btnGuardar.className = "btn btn-primary"
                        btnGuardar.style.backgroundColor = "#007bff"
                        btnGuardar.innerHTML = "Guardar Reporte"
                        btnGuardar.style.marginTop = "20px"
                        btnGuardar.addEventListener("click", function(){ checkEmptys(fecha) })

                        document.getElementById("reporteDiv").appendChild(div)
                        document.getElementById("reporteDiv").appendChild(btnGuardar)
                    }
                }else{
                    Swal.fire({
                        icon: "error",
                        title: "¡Error!",
                        text: "No se encontraron datos para la junta en la fecha: "+fecha+"."
                    })
                }
            },
            error: function (error){
                console.log(error)
            }
        })
    }

    function makeTitle(folio){
        let query = `SELECT nombreSistema FROM [dbo].[SCDSIS_Solicitud_registro_mst] WHERE folio = '${folio}';`
        $.ajax({
            type: "GET",
            url: "models/consultaDatos.php",
            data: {
                query: query,
            },
            success: function (data) {
                var arr = JSON.parse(data)
                if(arr.length > 0){
                    const folioTitle = document.createElement("h1")
                    folioTitle.innerHTML = `Folio: ${folio} - ${arr[0].nombreSistema}`
                    folioTitle.style.textAlign = "center"
                    folioTitle.style.marginTop = "20px"
                    document.getElementById("folioDiv").appendChild(folioTitle)
                }else{
                    Swal.fire({
                        icon: "error",
                        title: "¡Error!",
                        text: "No se encontró el sistema para el folio: "+folio+"."
                    })
                }
            },
            error: function (error){
                console.log(error)
            }
        })
    }

    function checkEmptys(fecha){
        var isValid = true

        if(metaCumplidaGlobal === 1){
            const inputIds = [
                "textareaMetaID",
                "textareaTareasCumplidasID",
                "textareaProblemasPresentadosID",
                "textareaPlanEjecucionID",
                "textareaNotasAdicionalesID",
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
                return false
            }
        }else{
            const inputIds = [
                "textareaMetaID",
                "textareaIncumplimientoID",
                "textareaTareasCumplidasID",
                "textareaProblemasPresentadosID",
                "textareaPlanEjecucionID",
                "textareaNotasAdicionalesID",
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
                return false
            }
        }
        safeDataJunta(fecha)
    }

    function safeDataJunta(fecha){
        let metaCumplida = document.getElementById("radioMetaCumplidaSiID").checked ? 1 : 0
        let query = `UPDATE [dbo].[SCDSIS_Solicitud_plan_trabajo_juntas_programadas] SET
        meta = '${document.getElementById("textareaMetaID").value}',
        metaCumplida = ${metaCumplida},
        incumplimientoRazon = '${document.getElementById("textareaIncumplimientoID").value}',
        tareasCumplidas = '${document.getElementById("textareaTareasCumplidasID").value}',
        problemasPresentados = '${document.getElementById("textareaProblemasPresentadosID").value}',
        planEjecucionSigSemana = '${document.getElementById("textareaPlanEjecucionID").value}',
        notasAdicionales = '${document.getElementById("textareaNotasAdicionalesID").value}'
        WHERE folio = '${folio}' AND fecha = '${fecha}';`

        $.ajax({
          type: "GET",
            url: "models/executaQuery.php",
            data: {
                query: query,
            },
            success: function (data) {
                Swal.fire({
                    icon: "success",
                    title: "¡Éxito!",
                    text: "Reporte guardado correctamente.",
                }).then(() => {
                    document.getElementById("reporteDiv").innerHTML = ""
                    document.getElementById("selectJunta").value = ""
                })
            },
            error: function (error){
                console.log(error)
                Swal.fire({
                    icon: "error",
                    title: "¡Error!",
                    text: "No se pudo guardar el reporte. Inténtelo de nuevo más tarde.",
                })
            }
        })
    }
})