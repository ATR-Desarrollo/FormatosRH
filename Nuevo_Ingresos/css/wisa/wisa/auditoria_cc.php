<?php include 'initialize.php'; ?>
<?php include 'header.php'; //AGREGA BARRA DE ENCABEZADO
?>
<?php include 'navbar.php'; ?>

<?php
if (!empty($_GET['r']) && !empty($_GET['c'])) {
    $Ruta = base64_decode($_GET['r']);
    $NomC = base64_decode($_GET['c']);
} else {
    echo '<script type="text/javascript">
    alert("Error al realizar consulta");
    window.location.href="consulta_solicitud_cc.php";
    </script>';
}

$sql = " [SP_SCCIS_CONSULTA_AUDITORIA] '$Ruta','$NomC' ";
$proc_result = sqlsrv_prepare($conn, $sql);
sqlsrv_execute($proc_result);

$sql = " [SP_SCCIS_INFO_CARPETA_AUDIT] '$NomC','$Ruta'";
$proc_carpeta = sqlsrv_prepare($conn, $sql);
sqlsrv_execute($proc_carpeta);
$infCarp = sqlsrv_fetch_array($proc_carpeta, SQLSRV_FETCH_ASSOC);
if ($infCarp != NULL) {
    $Planta = $infCarp['Planta'];
    $Depto = $infCarp['Departamento'];
    $noReloj = $infCarp['NoRelojResponsable'];
    $Usuario = $infCarp['Responsable'];
    $Correo = $infCarp['CorreoResponsable'];
    $Conf = $infCarp['Conf'];
} else {
    echo '<script type="text/javascript">
    alert("Carpeta no existente para auditoria.");
    window.location.href="consulta_solicitud_cc.php";
    </script>';
}


?>

<div id='contenido'>
    <!-- Page Content -->
    <div id='page-wrapper'>
        <div class='container-fluid'>
            <div class='row bg-title'>
                <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12" style="display:flex; justify-content:space-between;align-items:center;">
                    <h4 class='page-title'>Revision Auditoria de Carpetas Compartidas</h4>
                    <a href="consulta_solicitud_cc.php" role="button">
                        <button type="button" class="btn btn-info waves-effect waves-light">Ver solicitudes</button>
                    </a>
                </div>
                <div class='col-lg-9 col-sm-8 col-md-8 col-xs-12'>
                    <ol class='breadcrumb'></ol>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- .row -->
            <title> [Auditoria de CC] </title>
            <form action="auditoria_cc_add.php" method="POST" id="formReg">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="white-box">
                            <div class="form-horizontal form-material">
                                <div class="label-inverse">
                                    <label class="text-white">Consulta Carpeta</label>
                                </div>
                                </br>
                                <div style="display:flex; justify-content:space-between;">
                                    <div style="display:block;width:33%;">
                                        <label>No Reloj:</label>
                                        <input readonly type="text" class="form-control" id="nume" name="nume" value="<?php if (isset($infCarp)) {
                                                                                                                            echo $noReloj;
                                                                                                                        } ?>">
                                    </div>
                                    <div style="display:block;width:33%;">
                                        <label>Nombre de responsable:</label>
                                        <input readonly type="text" class="form-control" id="nomb" name="nomb" value="<?php if (isset($infCarp)) {
                                                                                                                            echo $Usuario;
                                                                                                                        } ?>">
                                    </div>
                                    <div style="display:block;width:33%;">
                                        <label>Correo de responsable:</label>
                                        <input readonly type="text" class="form-control" id="mail" name="mail" value="<?php if (isset($infCarp)) {
                                                                                                                            echo $Correo;
                                                                                                                        } ?>">
                                    </div>
                                </div>
                                </br>
                                <div style="display:flex; justify-content:space-between;">
                                    <div style="display:block;width:49%;">
                                        <label>Ruta:</label>
                                        <input readonly type="text" class="form-control" id="ruta" name="ruta" value="<?php if (isset($Ruta)) {
                                                                                                                            echo $Ruta;
                                                                                                                        } ?>">
                                    </div>
                                    <div style="display:block;width:49%;">
                                        <label>Nombre de carpeta:</label>
                                        <input readonly type="text" class="form-control" id="carp" name="carp" value="<?php if (isset($NomC)) {
                                                                                                                            echo $NomC;
                                                                                                                        } ?>">
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="planta" id="planta" value="<?php if (isset($infCarp)) {
                                                                                        echo $Planta;
                                                                                    } ?>">
                            <input type="hidden" name="depto" id="depto" value="<?php if (isset($infCarp)) {
                                                                                    echo $Depto;
                                                                                } ?>">
                            <input type="hidden" name="conf" id="conf" value="<?php if (isset($infCarp)) {
                                                                                    echo $Conf;
                                                                                } ?>">


                            <div class="form-horizontal form-material">
                                <br />
                                <hr />
                                <!-- <button type="button" class="btn btn-info" onclick="eliminarFila()"> Eliminar Usuario </button> 
                            <button type="button" class="btn btn-info" onclick="add()"> Agregar Usuario </button>  -->
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">No.</th>
                                            <th colspan="2" scope="col" class="col-md-3">Usuario</th>
                                            <th scope="col">Permiso Actual</th>
                                            <th scope="col" class="col-md-2">Permiso de Lectura</th>
                                            <th scope="col" class="col-md-2">Permiso de Escritura</th>
                                            <th scope="col" class="col-md-2">Eliminar Permisos </th>
                                        </tr>
                                    </thead>
                                    <?php
                                    $n = 1;
                                    ?>
                                    <?php if (isset($proc_result)) {
                                        while ($user_perm = sqlsrv_fetch_array($proc_result, SQLSRV_FETCH_ASSOC)) { ?>
                                            <tbody>
                                                <tr>
                                                    <td><?php echo "#" . $n ?></td>
                                                    <td colspan="2"><input style="pointer-events: none;" type="text" class="form-control" name="<?php echo 'usr' . $n ?>" value="<?php echo ($user_perm['Usuario']); ?>" readonly></td>
                                                    <td>
                                                        <p id="<?php echo 'actual' . $n; ?>"><?php echo ($user_perm['Permiso']); ?></p>
                                                        <input type="hidden" name="<?php echo 'usr' . $n . '_actual'; ?>" value="<?php echo trim($user_perm['Permiso']); ?>">
                                                    </td>
                                                    <td>
                                                        <input onchange="validaEstado(this)" type="radio" id="<?php echo 'permiso' . $n ?>" name="<?php echo 'usr' . $n . '_op' ?>" value="1"
                                                            <?php if (trim($user_perm['Permiso']) == "Solo Lectura"): ?>
                                                            checked
                                                            <?php endif ?>>
                                                    </td>
                                                    <td>
                                                        <input onchange="validaEstado(this)" type="radio" id="<?php echo 'permiso' . $n ?>" name="<?php echo 'usr' . $n . '_op' ?>" value="2"
                                                            <?php if (trim($user_perm['Permiso']) == "Lectura y Escritura"): ?>
                                                            checked
                                                            <?php endif ?>>
                                                    </td>
                                                    <td>
                                                        <input onchange="validaEstado(this)" type="radio" id="<?php echo 'permiso' . $n ?>" name="<?php echo 'usr' . $n . '_op' ?>" value="3"
                                                            <?php if (trim($user_perm['Permiso']) == "Eliminar Permisos"): ?>
                                                            checked
                                                            <?php endif ?>>
                                                    </td>
                                                </tr>
                                            </tbody>
                                    <?php $n = $n + 1;
                                        }
                                    } ?>
                                    <tbody id="tabla">
                                    </tbody>
                                </table>

                                <div class="col-md-6" style="text-align:center;">
                                    <button class="btn btn-info" onclick="btnAceptar_click()" id="btnAceptar" name="btnAceptar">Aceptar sin Modificaciones</button>
                                </div>

                                <div class="col-md-6" style="text-align:center;">
                                    <button class="btn btn-info" onclick="btnModificar_click()" id="btnModificar" name="btnModificar" disabled>Enviar Solicitud con Modificaciones</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    </body>

    <script>
        const domains = ["@sewsus.com.mx", "@sws.com", "@sewsus.com", "@ksmex.com.mx", "@sewsna.com"];

        var cnt = <?php echo $n; ?>;
        const min = <?php echo $n; ?>;
        var max = 40;
        var myTable = document.querySelector("table");

        var btnAceptar = document.getElementById("btnAceptar");
        var btnModificar = document.getElementById("btnModificar");

        var planta = "<?php echo $Planta; ?>";
        var depto = "<?php echo $Depto; ?>";
        var nomc = "<?php echo $NomC; ?>";
        var usuario = "<?php echo $Usuario; ?>";
        var noReloj = "<?php echo $noReloj; ?>";
        var conf = "<?php echo $Conf; ?>";
        var ruta = "<?php echo $Ruta; ?>";
        var correo = "<?php echo $Correo; ?>";

        var cntCambios = 0;

        function validaEstado(e) {
            var n = e.id.replace("permiso", "");
            var permiso = e.value;
            var actual = document.getElementById("actual" + n).innerHTML;

            console.log(permiso);
            console.log(actual);

            switch (permiso) {
                case "1":
                    if (actual == "Solo Lectura") {
                        cntCambios--;
                    } else {
                        cntCambios++;
                    }
                    break;
                case "2":
                    if (actual == "Lectura y Escritura") {
                        cntCambios--;
                    } else {
                        cntCambios++;
                    }
                    break;
                case "3":
                    if (actual == "Eliminar Permisos") {
                        cntCambios--;
                    } else {
                        cntCambios++;
                    }
                    break;
            }

            console.log(cntCambios);

            activarBtn();
        }

        function activarBtn() {
            if (cntCambios > 0 || cnt != min) {
                // btnAceptar.removeAttribute("disabled");
                // btnAceptar.innerHTML = "Aceptar sin Modificaciones";
                btnModificar.removeAttribute("disabled");
                // btnModificar.innerHTML = "Enviar Solicitud con Modificaciones";
            } else {
                // btnAceptar.setAttribute("disabled", true);
                // btnAceptar.innerHTML = "Sin Cambios";
                btnModificar.setAttribute("disabled", true);
                // btnModificar.innerHTML = "Sin Cambios";
            }
        }

        function btnAceptar_click() {
            btnAceptar.setAttribute("disabled", true);
            btnAceptar.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Guardando...';
            btnModificar.setAttribute("disabled", true);

            // sql = " INSERT INTO [WDB_APPS].[dbo].[SCCIS_AuditoriaCarpetas] VALUES('$Planta','$Depto','$NomC',GETDATE(),'$Usuario','OK') ";
            query = " INSERT INTO [WDB_APPS].[dbo].[SCCIS_AuditoriaCarpetas] VALUES('" + planta + "','" + depto + "','" + nomc + "',GETDATE(),'" + usuario + "','OK') ";

            $.ajax({
                type: "GET",
                url: "./models/executaQuery.php",
                data: {
                    query: query
                },
                success: function(data) {
                    if (data == "1") {
                        alert("Completado.");
                        window.location.href = "consulta_solicitud_cc.php";
                    } else {
                        alert("Error.");
                    }
                },
                error: function(data) {
                    alert("Error.");
                }
            });
        }

        function btnModificar_click() {
            // If the required fields are empty then don't submit the form
            $("form[id$='formReg']").submit(function(e) {
                var required = document.querySelectorAll("[required]");
                for (var i = 0; i < required.length; i++) {
                    if (!required[i].value) {
                        e.preventDefault();
                        // alert("Por favor complete todos los campos requeridos");
                        return;
                    }
                }

                btnAceptar.setAttribute("disabled", true);
                btnModificar.setAttribute("disabled", true);
                // btnModificar.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Guardando...';
            });

            $("form[id$='formReg']").submit();

            // alert(planta + depto + nomc + usuario + noReloj + conf + ruta + correo);
        }

        function eliminarFila() {
            var rowCount = myTable.rows.length;
            if (cnt <= min) {
                alert('No se pueden eliminar mas filas');
            } else {
                myTable.deleteRow(rowCount - 1);
                cnt--;
            }

            activarBtn();
        }

        function add() {
            var rowCount = myTable.rows.length;
            if (rowCount > max) {
                alert('No se pueden agregar mas filas');
            } else {
                // btnModificar.removeAttribute("disabled");

                pepe = document.getElementById('tabla');
                fila = document.createElement('tr');
                celda = document.createElement('td');

                fila.appendChild(celda);
                document.createTextNode("Hello World");
                document.createComment("kjsf");

                code = document.createElement("p");
                code.innerText = "#" + cnt;
                celda.appendChild(code);

                celda = document.createElement('td');
                fila.appendChild(celda);
                code = document.createElement('input');
                code.classList.add('form-control');
                code.type = 'text';
                code.name = 'usr' + cnt;
                code.required = true;
                code.placeholder = 'Correo del usuario';
                celda.appendChild(code);

                celda = document.createElement('td');
                fila.appendChild(celda);
                code = document.createElement('select');
                code.classList.add('form-control');
                code.name = 'usrDom' + cnt;

                for (i = 0; i < domains.length; i++) {
                    opt = document.createElement("option");
                    opt.value = domains[i];
                    opt.innerText = domains[i];
                    code.appendChild(opt);
                }

                celda.appendChild(code);

                celda = document.createElement('td');
                fila.appendChild(celda);
                code = document.createElement("p");
                code.innerText = "N/A";
                celda.appendChild(code);

                celda = document.createElement('td');
                fila.appendChild(celda);
                cant = document.createElement('input');
                cant.type = 'radio';
                cant.id = 'permiso' + cnt;
                cant.class = 'col-md-2';
                cant.name = 'usr' + cnt + '_op';
                cant.value = '1';
                cant.checked = 'TRUE';
                celda.appendChild(cant);

                celda = document.createElement('td');
                fila.appendChild(celda);
                cant = document.createElement('input');
                cant.type = 'radio';
                cant.id = 'permiso' + cnt;
                cant.class = 'col-md-2';
                cant.name = 'usr' + cnt + '_op';
                cant.value = '2';
                celda.appendChild(cant);

                celda = document.createElement('td');
                fila.appendChild(celda);
                cant = document.createElement('input');
                cant.type = 'radio';
                cant.id = 'permiso' + cnt;
                cant.class = 'col-md-2';
                cant.name = 'usr' + cnt + '_op';
                cant.value = '3';
                celda.appendChild(cant);
                pepe.appendChild(fila);
                cnt++;
                activarBtn();
            }
        }
    </script>

    </html>