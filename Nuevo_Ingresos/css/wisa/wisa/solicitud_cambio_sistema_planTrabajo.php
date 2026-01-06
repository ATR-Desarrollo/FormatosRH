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
            }
        }
    }
}
?>

<?php include "header.php";?>
<?php include "navbar.php";?>

<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="css/solicitud_cambio_sistema.css">
<style>
    .custom-modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background: rgba(0,0,0,0.5);
}

.custom-modal-content {
    background: #fff;
    margin: 10% auto;
    padding: 20px;
    border-radius: 8px;
    width: 90%;
    max-width: 400px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    text-align: center;
}

#btnGoTop, #btnGoBottom {
    position: fixed;
    z-index: 10000;
    width: 48px;
    height: 48px;
    border: none;
    border-radius: 50%;
    font-size: 24px;
    color: #fff;
    background: #007bff;
    cursor: pointer;
    opacity: 0.7;
    transition: opacity 0.2s;
}
#btnGoTop:hover, #btnGoBottom:hover {
    opacity: 1;
}
#btnGoTop {
    bottom: 32px;
    right: 32px;
}
#btnGoBottom {
    bottom: 32px;
    left: 32px;
}
</style>
<meta charset="UTF-8">
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div id="pageTitleDiv" class="col-lg-12 col-md-12"
                style="display:flex; justify-content:space-between;align-items:center;">
            </div>
        </div>

        <div class="row animated fadeInUp">
            <div class="col-md-12 white-box">
                <label class="text-black" style="display:flex; justify-content: center;" for="inputFolio">Folio:</label>
                <input id="selectFolio" class="form-control" name="folio" placeholder="Seleccione un folio">

                <input readonly id="inputFolio" type="text" class="form-control" value="">
                <br>

                <div class="col-md-6">
                    <label class="text-black" style="display:flex; justify-content: center;">Urgencia:</label>
                    <input readonly id="inputUrgente" type="text" class="form-control" placeholder="" value=""
                        name="nomb">
                </div>

                <div class="col-md-6">
                    <label class="text-black" style="display:flex; justify-content: center;">Estado:</label>
                    <input readonly id="inputEstado" type="text" class="form-control" placeholder="" value=""
                        name="nomb">
                </div>
                <br>

                <div class="col-md-12"><br></div>

                <div class="form-horizontal form-material">
                    <div class="col-md-12 label-inverse">
                        <label class="text-white">Datos del solicitante</label>
                    </div>
                    <br />
                    <div class="form-group">
                        <div class="col-md-6">
                            <label class="col-md-6" for="inputReloj">No Reloj:</label>
                            <input readonly id="inputReloj" type="text" class="form-control" value="">
                        </div>

                        <div class="col-md-6">
                            <label class="col-md-6" for="inputFecha">Fecha:</label>
                            <input readonly id="inputFecha" type="text" class="form-control" value="">
                        </div>
                    </div>


                    <div class="col-md-6">
                        <label class="col-md-6" for="inputNombre">Nombre:</label>
                        <input readonly id="inputNombre" type="text" class="form-control" placeholder="" value="">
                    </div>

                    <div class="col-md-6">
                        <input readonly id="inputCorreo" type="text" class="form-control" placeholder="" value=""
                            name="correo">
                    </div>

                    <div class="col-md-6">
                        <label class="col-md-6" for="inputDepartamento">Departamento:</label>
                        <input readonly id="inputDepartamento" type="text" class="form-control" value="">
                    </div>

                    <div class="col-md-6">
                        <label class="col-md-6" for="inputNombreEncargado">Encargado:</label>
                        <input readonly id="inputNombreEncargado" type="text" class="form-control" value="">
                        <br>
                    </div>


                    <div class="col-md-12 label-inverse">
                        <label class="text-white">Requerimientos</label>
                    </div>

                    <div class="col-md-6">
                        <label for="inputTipoSolicitud">Tipo de Solicitud:</label>
                        <input readonly id="inputTipoSolicitud" type="text" class="form-control" value="">
                    </div>

                    <div class="col-md-6">
                        <label for="inputTipoTrabajo">Tipo de Trabajo:</label>
                        <input readonly id="inputTipoTrabajo" type="text" class="form-control" value="">
                    </div>

                    <div class="col-md-6">
                        <label class="col-md-6" for="inputNombreSistema">Nombre del Sistema:</label>
                        <input readonly id="inputNombreSistema" type="text" class="form-control" value="">
                    </div>

                    <div class="col-md-6">
                        <label class="col-md-6" for="inputNombreModRep">Nombre del Modulo/Reporte:</label>
                        <input readonly id="inputNombreModRep" type="text" class="form-control" value="">
                    </div>

                    <div class="col-md-6">
                        <label class="col-md-6" for="inputNumeroUsuarios">Numero de Usuarios:</label>
                        <input readonly id="inputNumeroUsuarios" type="text" class="form-control" value="">
                    </div>

                    <div class="col-md-6">
                        <label class="col-md-6" for="inputDepartsInvol">Departamentos Involucrados:</label>
                        <input readonly id="inputDepartsInvol" type="text" class="form-control" value="">
                    </div>

                    <div class="col-md-12">
                        <label for="inputCapacitacion">Requieren Capacitacion:</label>
                        <input readonly id="inputCapacitacion" type="text" class="form-control" value="">
                        <br>
                    </div>

                    <div class="col-md-12 label-inverse">
                        <label class="text-white">Descripcion de la Solicitud</label>
                    </div>

                    <div class="col-md-12">
                        <input readonly id="inputDescripcion" type="text" class="form-control" value="">
                        <br>
                    </div>

                    <div class="col-md-12" style="display:flex; justify-content: center;">
                        <input value="Ver Archivo" class="btn btn-info" onclick="seeFile()" id="fileInput">
                    </div>

                    <div class="col-md-12"><br></div>

                    <div class="col-md-12 label-inverse">
                        <label class="text-white">Retorno de inversión y disminución de headcount</label>
                    </div>

                    <div class="col-md-12">
                        <br>
                        <div class="col-md-6 label-inverse">
                            <label class="text-white">ROI</label>
                        </div>
                        <div class="col-md-6 label-inverse">
                            <label class="text-white">HEADCOUNT</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <br>
                        <label class="col-md-3" for="inputEquipo">Equipo:</label>
                        <input readonly id="inputEquipo" class="col-md-9" type="number" class="form-control"
                            placeholder="USD $" value="">

                        <label class="col-md-3" for="inputSoftware">Software:</label>
                        <input readonly id="inputSoftware" class="col-md-9" type="number" class="form-control"
                            placeholder="USD $" value="">

                        <label class="col-md-3" for="inputSalario">Salario:</label>
                        <input readonly id="inputSalario" class="col-md-9" type="number" class="form-control"
                            placeholder="USD $" value="">

                        <label class="col-md-3" for="inputAhorro">Ahorro:</label>
                        <input readonly id="inputAhorro" class="col-md-9" type="number" class="form-control"
                            placeholder="USD $" value="">

                        <label class="col-md-3">ROI:</label>
                        <input readonly id="inputROI" class="col-md-9" type="text" class="form-control" placeholder="0"
                            value="">
                    </div>

                    <div class="col-md-6">
                        <label class="col-md-6" for="inputAreaDepart">Area/Departamento:</label>
                        <input readonly id="inputAreaDepart" type="text" class="form-control" placeholder="" value="">
                        <div class="col-md-12">
                            <label class="col-md-4">Posicion</label>
                            <label class="col-md-2">Directos</label>
                            <label class="col-md-2">Indirectos</label>
                            <label class="col-md-2">Admin.</label>
                            <label class="col-md-2">Total</label>
                        </div>
                        <div class="col-md-12">
                            <label class="col-md-4">Antes</label>
                            <input readonly id="inputAntesDir" class="col-md-2" type="number" class="form-control"
                                placeholder="0" value="">
                            <input readonly id="inputAntesInd" class="col-md-2" type="number" class="form-control"
                                placeholder="0" value="">
                            <input readonly id="inputAntesAdm" class="col-md-2" type="number" class="form-control"
                                placeholder="0" value="">
                            <input readonly id="inputAntesTotal" class="col-md-2" type="number" class="form-control"
                                placeholder="0" value="">
                        </div>
                        <div class="col-md-12">
                            <label class="col-md-4">Despues</label>
                            <input readonly id="inputDespDir" class="col-md-2" type="number" class="form-control"
                                placeholder="0" value="">
                            <input readonly id="inputDespInd" class="col-md-2" type="number" class="form-control"
                                placeholder="0" value="">
                            <input readonly id="inputDespAdm" class="col-md-2" type="number" class="form-control"
                                placeholder="0" value="">
                            <input readonly id="inputDespTotal" class="col-md-2" type="number" class="form-control"
                                placeholder="0" value="">
                        </div>
                        <div class="col-md-12">
                            <label class="col-md-4">Diferencia</label>
                            <input readonly id="inputDifeDir" class="col-md-2" type="number" class="form-control"
                                placeholder="0" value="">
                            <input readonly id="inputDifeInd" class="col-md-2" type="number" class="form-control"
                                placeholder="0" value="">
                            <input readonly id="inputDifeAdm" class="col-md-2" type="number" class="form-control"
                                placeholder="0" value="">
                            <input readonly id="inputDifeTotal" class="col-md-2" type="number" class="form-control"
                                placeholder="0" value="">
                        </div>
                    </div>

                    <div class="col-md-12"><br></div>

                    <!--- Plan de Trabajo --->
                    <div class="col-md-12 label-inverse">
                        <label class="text-white">Propuesta de Plan de Trabajo y Fechas de Entrega</label>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Proyecto Factible</label>
                            <div>
                                <input type="radio" id="proyectoSi" name="proyectoFactible" checked value="1">
                                <label for="proyectoSi">Si</label>
                                <input type="radio" id="proyectoNo" name="proyectoFactible" value="0">
                                <label for="proyectoNo">No</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <!-- Razón -->
                            <div class="col-md-6">
                                <label for="inputRazonSis">Razón:</label>
                                <input id="inputRazonSis" type="text" class="form-control"
                                    placeholder="Escribir solo si NO es factible" maxlength="100" autocomplete="off" disabled>
                            </div>

                            <!-- No De Ticket Asignado -->
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="inputTicketSis">No De Ticket Asignado:</label>
                                    <input id="inputTicketSis" type="number" class="form-control" maxlength="10">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <!-- Responsable -->
                                <div class="col-md-6">
                                    <label for="inputNumeroEmpleadoSis">No. Empleado:</label>
                                    <input id="inputNumeroEmpleadoSis" type="number" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="inputResponsableSis">Responsable:</label>
                                    <input readonly id="inputResponsableSis" type="text" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <!-- Duracion del Proyecto -->
                                <div class="col-md-2">
                                    <label for="inputInicioSis">Inicio:</label>
                                    <input id="inputInicioSis" type="date" class="form-control">
                                </div>

                                <div class="col-md-2">
                                    <label for="inputFinSis">Fin:</label>
                                    <input id="inputFinSis" type="date" class="form-control" disabled>
                                </div>

                                <div class="col-md-8">
                                    <label for="selectDia">Día de la semana:</label>
                                    <select disabled id="selectDia" class="form-control">
                                        <option value="">Seleccione un Día</option>
                                        <option value="1">Lunes</option>
                                        <option value="2">Martes</option>
                                        <option value="3">Miércoles</option>
                                        <option value="4">Jueves</option>
                                        <option value="5">Viernes</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="fechas" class="form-horizontal form-material"></div>

                        <!-- Recursos o herramientas especiales requeridos -->
                        <div class="col-md-12 form-group">
                            <label for="inputRecursosSis">Recursos o herramientas especiales requeridos:</label>
                            <textarea id="inputRecursosSis" type="text" class="form-control" maxlength="200"
                                autocomplete="off" rows="4"></textarea>
                            <span class="pull-right label label-default" id="count_message"></span>
                        </div>

                        <input id="btnProyectos" class="btn btn-info" type="submit" name="btnSolicitar"
                            value="Agregar Proyectos" onclick="btnAddProjects()">

                        <div id="proyectos" class="form-horizontal form-material">
                            <br>
                        </div>
                        <br>
                        <br>
                        <!-- Juntas Programadas -->
                        <div class="col-md-12">
                            <label>Juntas Programadas</label>
                            <div>
                                <input readonly id="proyectoJuntas" type="text" class="form-control" placeholder="-" value="">
                                <br>
                                <div id="divJuntas">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Fechas De las Juntas</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodydivJuntas"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12" id="divProyectos">
                            <table class="centered" id="">
                                <thead class="">
                                    <tr>
                                        <th>Proyecto</th>
                                        <th>Duracion</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodydivProyectos"></tbody>
                            </table>
                        </div>

                        <div class="col-md-12">
                            <div class="col-md-12" style="display:flex; justify-content: center;">
                                <!-- <input id="btnAutorizar" class="btn btn-info" type="submit" name="btnSolicitar"
                                    value="Registrar plan" onclick="checkEmptys()"> -->
                            </div>
                        </div>

                    </div>
                    <!--- Fin --->

                    <div id="botonesDiv" class="col-md-12">
                        <br><br>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Rechazo Structure -->
        <div class="custom-modal" id="myModal">
            <div class="custom-modal-content">
                <h2>Motivo de Rechazo</h2>
                <input id="inputRechazoMotivo" type="text" class="form-control"
                    placeholder="Escriba el motivo de Rechazo:">
                <br>
                <button id="closeModal">Cerrar</button>

                <button id="btnConfirmarRechazo">Confirmar</button>
            </div>
        </div>

        <!-- Modal Cambio de Plan Structure -->
        <div class="custom-modal" id="myModal2">
            <div class="custom-modal-content">
                <h2>Motivo de Cambio de Plan</h2>
                <input id="inputCambioPlanMotivo" type="text" class="form-control"
                    placeholder="Escriba el motivo de Cambio de Plan:">
                <br>
                <button id="closeModal2">Cerrar</button>

                <button id="btnConfirmarCambioPlan">Confirmar</button>
            </div>
        </div>
    </div>
    
    <button id="btnGoTop" title="Ir arriba">&#8679;</button>
    <button id="btnGoBottom" title="Ir abajo">&#8681;</button>
</div>

<script src="plugins/bower_components/jquery/dist/jquery.min.js"></script>
<script src="js/solicitud_cambio_sistema_planTrabajo.js"></script>
<script src="./js/sweetalert2.all.js"></script>
<?php include("footer.php");?>

</html>