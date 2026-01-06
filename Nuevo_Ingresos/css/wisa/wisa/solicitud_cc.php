<?php
//
session_start();

include("conexion.php");

$row = 1;

$sql = "SP_CARGA_PLANTAS";
$proc_plantas = sqlsrv_prepare($conn, $sql);
sqlsrv_execute($proc_plantas);

$sql = "SP_CARGA_DEPTOS";
$proc_deptos = sqlsrv_prepare($conn, $sql);
sqlsrv_execute($proc_deptos);

if (isset($_POST["NoReloj"])) { // <= true
    $NoCtr = $_POST["NoReloj"];
    if (isset($_POST["correo"])) {
        $Correo = $_POST['correo'];
    }

    $sql ="SELECT * FROM Usuarios WHERE noreloj=".$NoCtr;
    $params=array();
    $options=array("Scrollable" => SQLSRV_CURSOR_KEYSET );

    $stmt = sqlsrv_query( $conn, $sql , $params, $options );
    $row_count = sqlsrv_num_rows( $stmt );
    $row=sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);

    if ($row_count === false) { // SI NO EXISTE EL no ctrl, MANDA MENSAJE DE ERROR
        echo'<script type="text/javascript">
        alert("Error al realizar consulta");
        window.location.href="usuarios.php";
        </script>';
    } else {
        if($row_count > 0) { //si existe el usuario

        } else {
            $sql = " [SP_CONSULTA_DATOS_ASOCIADO] '".$NoCtr."' ";

            $params=array();
            $options=array("Scrollable" => SQLSRV_CURSOR_KEYSET );

            $stmt = sqlsrv_query( $conn, $sql , $params, $options );
            $row_count = sqlsrv_num_rows( $stmt );
            $row=sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);

            if (! is_null($row)){
                $Planta = $row['Planta'];
                $Depto = ($row['Departamento']);

                // $sql = " [SP_SCCIS_CARGA_CARPETAS] '". $Planta."', '".($Depto)."' ";
                // $proc_carpetas = sqlsrv_prepare($conn, $sql);
                // sqlsrv_execute($proc_carpetas);
            }
        }
    }
}

// if (isset($_POST["btnCarpetas"])) {
// $Planta = $_POST['planta'];
// $Depto = $_POST['dpto'];

// $sql = " [SP_SCCIS_CARGA_CARPETAS] '". $Planta."', '".($Depto)."' ";
// $proc_carpetas = sqlsrv_prepare($conn, $sql);
// sqlsrv_execute($proc_carpetas);
// }
?>

<?php include "header.php";?>
<?php include "navbar.php";?>

<!DOCTYPE html>
<html lang="en">

<!-- Modal AGREGAR-->
<meta charset="UTF-8">

    <form action="solicitud_cc_add.php" method="post" enctype="multipart/form-data">
        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="formulario"
            aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Capture la informacion del usuario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="elem-group"> <label class="col-md-12">Usuario:</label>
                            <div class="col-md-10"><input type="text" class="form-control" placeholder="" value=""
                                    name="titulo">
                                <input type="hidden" class="form-control" placeholder="" value="<?php echo $NoCtr; ?>"
                                    name="idcurs">
                            </div>
                        </div>
                        <div class="elem-group">
                            <label class="col-md-12">Permisos:</label>
                        </div>
                        <div class="elem-group">
                            <label class="col-md-4">Lectura</label>
                            <label class="col-md-4">Escritura</label>
                            <label class="col-md-4">Eliminar</label>
                        </div>
                        <div class="elem-group">
                            <input class="col-md-4" type="checkbox" id="lect" name="lect"
                                value="Lectura">
                            <input class="col-md-4" type="checkbox" id="escr" name="escr" value="Escritura">
                            <input class="col-md-4" type="checkbox" id="elim" name="elim" value="Eliminar">
                        </div>
                    </div>
                    <div class="elem-group">
                        <button type="button" class="btn btn-info waves-effect waves-light m-t-10"
                            data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-info waves-effect waves-light m-t-10">Agregar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12" style="display:flex; justify-content:space-between;align-items:center;">
                    <h4 class="page-title">W IS A - Solicitud de acceso/creacion de carpetas compartidas</h4>
                    <a href="consulta_solicitud_cc.php" role="button">
                        <button type="button" class="btn btn-info waves-effect waves-light">Ver solicitudes</button>
                    </a>
                    <a href="./Resources//Manual de usuario - Sistema de solicitudes de Carpeta Compartida.pdf" download="Manual de usuario - Sistema de solicitudes de Carpeta Compartida.pdf" role="button">
                        <button type="button" class="btn btn-secondary">Descargar manual</button>
                    </a>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12">
                    <div class="white-box">
                        <form class="form-horizontal form-material" action="solicitud_cc.php" method="post">
                            <div class="label-inverse">
                                <label class="text-white">Datos del solicitante</label>
                            </div>
                            <br/>
                            <div class="form-group">
                                <label class="col-md-12">No. Reloj: </label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" placeholder=""
                                        value="<?php if (isset( $_POST["NoReloj"])) { echo $NoCtr; }?>" name="NoReloj" required>
                                </div>
                                    <div class="col-md-8"> <input class="btn btn-info" type="submit" name="btnConsultar" value="Consultar">
                                </div>
                            </div>
                        </form>

                        <form class="form-horizontal form-material" action="solicitud_cc.php" method="POST">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label class="col-md-6">Nombre:</label>
                                    <input readonly type="text" class="form-control" placeholder=""
                                        value="<?php if (isset( $_POST["NoReloj"])) { if (! is_null($row)) { echo $row['Nombre'];}} ?>"
                                        name="nomb" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="col-md-6">Correo:</label>
                                    <input type="text" class="form-control" placeholder=""
                                        value="<?php if (isset($Correo)) { echo $Correo;} else if (isset( $_POST["NoReloj"])) { if (! is_null($row)) { echo $row['Correo'];}} ?>"
                                        name="correo" required>
                                    <input type="hidden" class="form-control" placeholder=""
                                        value="<?php if (isset( $_POST["NoReloj"])) { echo $NoCtr;} ?>" name="nume">
                                </div>
                            </div>

                            <div class="label-inverse">
                                <label class="text-white">Requerimientos</label>
                            </div>
                            <br/>
                            
                            <input type="hidden" value="<?php if (isset( $_POST["NoReloj"])) { echo $NoCtr; }?>" name="NoReloj">
                            <div id="RadioTipoSoli">
                                <label class="col-md-12">Tipo de solicitud:</label>
                                <div class="form-group" style="display:flex; justify-content:space-between;align-items:center;">
                                    <label class="col-md-2 container">Modificar carpeta
                                        <input class="col-md-2" type="radio" name="tipo" checked value="Modifica">
                                    </label>
                                    <label class="col-md-2 container">Crear carpeta
                                        <input class="col-md-2" type="radio" name="tipo" value="Nuevo">
                                    </label>
                                    <label class="col-md-2 container">Eliminar carpeta
                                        <input class="col-md-2" type="radio" name="tipo" value="Elimina">
                                    </label>
                                </div>

                                <div class="form-group ">
                                    <label class="col-md-2">Planta:</label>
                                    <div class="col-md-4">
                                        <select id="planta" class="form-control" name="planta" onchange="">
                                            <option value="<?php if (isset($Planta)) {echo $Planta;} else { echo $row['Planta'];} ?>"><?php if (isset($Planta)) {echo $Planta;} else if (isset($row['Planta'])) {echo $row['Planta'];} ?></option>
                                            <?php while($plantas=sqlsrv_fetch_array($proc_plantas, SQLSRV_FETCH_ASSOC)) { ?>
                                                <option value="<?php echo $plantas['Planta'] ; ?>"><?php echo $plantas['Planta']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <label class="col-md-2">Departamento:</label>
                                    <div class="col-md-4">
                                        <select id="dpto" class="form-control" name="dpto">
                                            <option value="<?php if (isset($Depto)) {echo $Depto;} else { echo $row['Departamento'];} ?>"><?php if (isset($Depto)) {echo $Depto;} else if (isset($row['Departamento'])) {echo ($row['Departamento']);} ?></option>
                                            <!-- <?php //while($deptos=sqlsrv_fetch_array($proc_deptos, SQLSRV_FETCH_ASSOC)) { ?>
                                                <option value="<?php //echo ($deptos['Departamento']); ?>"><?php //echo ($deptos['Departamento']); ?></option>
                                            <?php //} ?> -->
                                        </select>
                                    </div>
                                </div>
                                <div id="Modifica" class="desc">    
                                    <div class="form-group ">
                                        <label class="col-md-2">Nombre de Carpeta:</label>
                                        <select class="col-md-4" id="carpM">
                                            <option value=""></option>
                                            <?php if(isset($proc_carpetas)) { while($Carpetas=sqlsrv_fetch_array($proc_carpetas, SQLSRV_FETCH_ASSOC)) { ?>
                                                <option value="<?php echo $Carpetas['Ruta']; ?>"><?php echo $Carpetas['NombreCarpeta']; ?></option>
                                            <?php }} ?>
                                        </select>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-info" name="btnCarpetas" id="btnCarpetas">Buscar carpetas</button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2">Ruta de la carpeta:</label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" placeholder="" value="" id="rutaM" readonly>
                                        </div>
                                        <label class="col-md-2">Motivo:</label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" placeholder="" value="" id="motiM" maxlength="50">
                                        </div>
                                    </div>
                                </div>

                                <div id="Nuevo" class="desc">
                                    <div class="form-group ">
                                        <label class="col-md-2">Nombre de Carpeta:</label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" placeholder="" value="" id="carpC">
                                        </div>
                                        <label class="col-md-2">Nivel de confidencialidad:</label>
                                        <select class="col-md-4" id="confC">
                                            <option value="General"> Acceso General</option>
                                            <option value="Confidencial"> Confidencial </option>
                                            <option value="Sensible"> Datos sensibles de personal</option>
                                        </select>
                                    </div>
                                    <div class="form-group ">
                                        <label class="col-md-2">Tamaño Estimado (Gb):</label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" placeholder="" value="" id="tamaC">
                                        </div>
                                        <label class="col-md-2">Tipos de archivos:</label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" placeholder="" value="" id="tipaC">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-1">Motivo:</label>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" placeholder="" value="" id="motiC" maxlength="50">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <form class="form-horizontal form-material" action="solicitud_cc_add.php" method="POST" id="formReg">
                            <div class="label-inverse">
                                <label class="text-white">Especificacion de usuarios para autorizacion</label>
                            </div>
                            <br/>
                            <!-- Button trigger modal -->
                            <div class="col-md-12"> 
                            <button type="button" class="btn btn-info" onclick="eliminarFila()"> Eliminar Usuario</button> 
                            <button type="button" class="btn btn-info" onclick="add()"> Agregar Usuario</button> 
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col" class="col-md-1">               No.                     </th>
                                        <th colspan="2" scope="col" class="col-md-5">   Usuario                 </th>
                                        <th scope="col" class="col-md-2">               Permiso de Lectura      </th>
                                        <th scope="col" class="col-md-2">               Permiso de Escritura    </th>
                                        <th scope="col" class="col-md-2">               Eliminar Permisos       </th>
                                    </tr>
                                </thead>
                                <tbody id="tabla">
                                    
                                </tbody>
                            </table>
                            </div>
                            <br>
                            <button type="submit" onclick="guardar()" class="btn btn-info waves-effect waves-light m-t-10" name="btnReg" id="btnReg" title='Tiene que agregar por lo menos un usuario' disabled="true">Registrar</button>
                            <br>

                            <!-- Variables POST -->
                            <input type="hidden" class="form-control" name="nume" value="<?php echo $NoCtr; ?>">
                            <input type="hidden" class="form-control" name="nomb" value="<?php echo $row['Nombre']; ?>">
                            <input type="hidden" class="form-control" name="plnt" id="plntSel" value="">
                            <input type="hidden" class="form-control" name="dept" id="deptSel" value="">
                            <input type="hidden" class="form-control" name="tipo" id="tipo" value="Modifica">
                            
                            <input type="hidden" class="form-control" name="mail" id="mailFin" value="">
                            <input type="hidden" class="form-control" name="carp" id="carpFin" value="">
                            <input type="hidden" class="form-control" name="ruta" id="rutaFin" value="">
                            <input type="hidden" class="form-control" name="conf" id="confFin" value="">
                            <input type="hidden" class="form-control" name="moti" id="motiFin" value="">
                            <input type="hidden" class="form-control" name="tama" id="tamaFin" value="">
                            <input type="hidden" class="form-control" name="tipa" id="tipaFin" value="">
                        </form>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="plugins/bower_components/jquery/dist/jquery.min.js"></script>
    
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script> -->
    <script>
        const domains = ["@sewsus.com.mx", "@sws.com", "@sewsus.com", "@ksmex.com.mx","@sewsna.com"];
        
        $(document).ready(function() {
            $("div.desc").hide();
            $("#Modifica").show();

            $("form[id$='formReg']").submit(function(e){
                if($("#tipo").val() == 'Nuevo') {
                    if($("input[name$='correo']").val() == "" || $("#carpC").val() == "" || $("#rutaC").val() == "" || $("#tamaC").val() == "" || $("#tipaC").val() == "" || $("#motiC").val() == "" ) {
                        alert("Tienes que llenar todos los campos.");
                        e.preventDefault();
                    }
                    $("#carpFin").val($("#carpC").val());
                    $("#rutaFin").val("");
                    $("#confFin").val($("#confC").val());
                    $("#motiFin").val($("#motiC").val());
                    $("#tamaFin").val($("#tamaC").val());
                    $("#tipaFin").val($("#tipaC").val());
                    $("#deptSel").val($("#dpto option:selected").val());
                    $("#plntSel").val($("#planta option:selected").val());

                } else if ($("#tipo").val() == 'Modifica' || $("#tipo").val() == 'Elimina' ) {
                    if($("input[name$='correo']").val() == "" || $("#carpM option:selected").text() == "" || $("#motiM").val() == "" ) {
                        alert("Tienes que llenar todos los campos.");
                        e.preventDefault();
                    }
                    $("#carpFin").val($("#carpM option:selected").text());
                    $("#rutaFin").val($("#carpM").val());
                    $("#confFin").val("");
                    $("#motiFin").val($("#motiM").val());
                    $("#tamaFin").val("");
                    $("#tipaFin").val("");
                    $("#deptSel").val($("#dpto option:selected").val());
                    $("#plntSel").val($("#planta option:selected").val());
                }
                $("#mailFin").val($("input[name$='correo']").val());
            });

            $("input[name$='tipo']").click(function() {
                if($(this).val() == 'Nuevo') {
                    $("div.desc").hide();
                    $("#Nuevo").show();
                } else if ($(this).val() == 'Modifica' || $(this).val() == 'Elimina' ) {
                    $("div.desc").hide();
                    $("#Modifica").show();
                }
                $("#tipo").val(this.value);
            });

            $("select[id$='carpM']").on('change', function (e) {
                $("#rutaM").val(this.value);
            });

            $("select[id$='planta']").on('change', function (e) {
                consultaDeptos(this.value);
            });

            if ($("select[id$='planta']").val() != "") {
                consultaDeptos($("select[id$='planta']").val());
            }

            $("select[id$='dpto']").on('change', function (e) {
                buscaCarpetas();
            });

            $("button[id$='btnCarpetas']").click(function() {
                buscaCarpetas();
            });
        });

        var n=0;
        var max=40;
        var myTable = document.querySelector("table");
        var btnReg = document.getElementById("btnReg");

        function validaExiste(nombreCarpeta, planta, depto) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: './models/valida_carpeta.php',
                    type: 'GET',
                    data: {
                        nombreCarpeta: nombreCarpeta,
                        planta: planta,
                        depto: depto
                    },
                    success: function(data) {
                        console.log(data);
                        resolve(data);
                    },
                    error: function(data) {
                        alert('Error: ' + data);
                        reject(data);
                    }
                });
            });
        }

        function buscaCarpetas() {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: './models/consulta_carpetas.php',
                    type: 'GET',
                    data: {
                        planta: $("#planta option:selected").text(),
                        depto: $("#dpto option:selected").text()
                    },
                    success: function(data) {
                        data = JSON.parse(data);

                        if (data.length > 0) {
                            var select = document.getElementById("carpM");
                            select.innerHTML = "";
                            select.innerHTML += "<option value=''></option>";
                            for (var i = 0; i < data.length; i++) {
                                select.innerHTML += "<option value='" + data[i].Ruta + "'>" + data[i].NombreCarpeta + "</option>";
                            }
                        } else {

                        }
                    },
                    error: function(data) {
                        alert('Error: ' + data);
                        reject(data);
                    }
                });
            });
        }

        function consultaDeptos(planta) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: './models/consulta_deptos.php',
                    type: 'GET',
                    data: {
                        planta: planta
                    },
                    success: function(data) {
                        data = JSON.parse(data);

                        if (data.length > 0) {
                            var select = document.getElementById("dpto");
                            select.innerHTML = "";
                            select.innerHTML += "<option value=''>Selecciona un departamento</option>";
                            for (var i = 0; i < data.length; i++) {
                                select.innerHTML += "<option value='" + data[i].Departamento + "'>" + data[i].Departamento + "</option>";
                            }
                            resolve(data);
                        } else {
                            var select = document.getElementById("dpto");
                            select.innerHTML = "<option value=''>No hay departamentos</option>";
                            resolve(data);
                        }
                    },
                    error: function(data) {
                        alert('Error: ' + data);
                        reject(data);
                    }
                });
            });
        }

        async function guardar() {
            $("form[id$='formReg']").off('submit');

            $("form[id$='formReg']").on('submit', async function(e){
                e.preventDefault();

                var required = document.querySelectorAll("[required]");
                for (var i = 0; i < required.length; i++) {
                    if (!required[i].value) {
                        e.preventDefault();
                        //alert("Por favor complete todos los campos requeridos");
                        return;
                    }
                }

                if($("#tipo").val() == 'Nuevo') {
                    if($("input[name$='correo']").val() == "" || $("#carpC").val() == "" || $("#rutaC").val() == "" || $("#tamaC").val() == "" || $("#tipaC").val() == "" || $("#motiC").val() == "" ) {
                        alert("Tienes que llenar todos los campos.");
                        return;
                    }

                    try {
                        const existe = await validaExiste($("#carpC").val(), $("#planta").val(), $("#dpto").val());
                        if (existe == "1") {
                            alert("Error: Carpeta ya existente.");
                            return;
                        }
                    } catch (error) {
                        console.error("Error validating existence:", error);
                        return;
                    }

                    $("#carpFin").val($("#carpC").val());
                    $("#rutaFin").val("");
                    $("#confFin").val($("#confC").val());
                    $("#motiFin").val($("#motiC").val());
                    $("#tamaFin").val($("#tamaC").val());
                    $("#tipaFin").val($("#tipaC").val());
                    $("#deptSel").val($("#dpto").val());
                    $("#plntSel").val($("#planta option:selected").val());

                } else if ($("#tipo").val() == 'Modifica' || $("#tipo").val() == 'Elimina' ) {
                    if($("input[name$='correo']").val() == "" || $("#carpM option:selected").text() == "" || $("#motiM").val() == "" ) {
                        alert("Tienes que llenar todos los campos.");
                        e.preventDefault();
                    }
                    $("#carpFin").val($("#carpM option:selected").text());
                    $("#rutaFin").val($("#carpM").val());
                    $("#confFin").val("");
                    $("#motiFin").val($("#motiM").val());
                    $("#tamaFin").val("");
                    $("#tipaFin").val("");
                    $("#deptSel").val($("#dpto option:selected").val());
                    $("#plntSel").val($("#planta option:selected").val());
                }

                 // Deshabilita el boton para evitar que se envie varias veces
                btnReg.setAttribute("disabled","true");
                // Muestra el cursor de cargando
                btnReg.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Guardando...';

                $("#mailFin").val($("input[name$='correo']").val());

                // Envia el formulario
                this.submit();
            });
            // $("form[id$='formReg']").submit();
        }

        function eliminarFila(){
            var rowCount = myTable.rows.length;
            // alert(rowCount);
            if (rowCount == 2) {
                btnReg.setAttribute("disabled","true");
                btnReg.setAttribute("title",'Tiene que agregar por lo menos un usuario');
            }
            if (rowCount <= 1) {
                alert('No se pueden eliminar mas filas');
            } else {
                myTable.deleteRow(rowCount -1);
                n--;
            }
        }

        function add() {
            var rowCount = myTable.rows.length;
            btnReg.removeAttribute("disabled");
            btnReg.removeAttribute('title');
            if(rowCount > max) {
                alert('No se pueden agregar mas filas');
            } else {
                n++;
                pepe = document.getElementById('tabla');
                fila = document.createElement('tr');
                celda = document.createElement('td');

                fila.appendChild(celda);
                document.createTextNode("Hello World");
                document.createComment("kjsf");

                code = document.createElement("p");
                code.innerText = "#"+n;
                celda.appendChild(code);

                celda = document.createElement('td');
                fila.appendChild(celda);
                code=document.createElement('input');
                code.classList.add('form-control');
                code.type='text';
                code.name='usr'+n;
                code.required=true;
                code.placeholder='Correo del usuario...';
                celda.appendChild(code);

                celda = document.createElement('td');
                fila.appendChild(celda);
                code=document.createElement('select');
                code.classList.add('form-control');
                code.name='usrDom'+n;

                for (i=0;i<domains.length;i++) {
                    opt = document.createElement("option");
                    opt.value = domains[i];
                    opt.innerText = domains[i];
                    code.appendChild(opt);
                }

                celda.appendChild(code);

                celda = document.createElement('td');
                fila.appendChild(celda);
                cant=document.createElement('input');
                cant.type='radio';
                cant.id='permiso'+n;
                cant.classList.add('col-md-4');
                cant.name='usr'+n+'_op';
                cant.value='1';
                cant.checked='TRUE';
                celda.appendChild(cant);

                celda = document.createElement('td');
                fila.appendChild(celda);
                cant=document.createElement('input');
                cant.type='radio';
                cant.id='permiso'+n;
                cant.classList.add('col-md-4');
                cant.name='usr'+n+'_op';
                cant.value='2';
                celda.appendChild(cant);

                celda = document.createElement('td');
                fila.appendChild(celda);
                cant=document.createElement('input');
                cant.type='radio';
                cant.id='permiso'+n;
                cant.classList.add('col-md-4');
                cant.name='usr'+n+'_op';
                cant.value='3';
                celda.appendChild(cant);
                pepe.appendChild(fila);
            }   
        }
    </script>
    <?php include("footer.php");?>
</html>
