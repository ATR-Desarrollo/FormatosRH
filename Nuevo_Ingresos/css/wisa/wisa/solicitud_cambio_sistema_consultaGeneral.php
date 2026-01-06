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
tr:hover {
    background-color: rgb(148, 188, 248);
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
<!-- Modal AGREGAR-->
<meta charset="UTF-8">

<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12"
                style="display:flex; justify-content:space-between;align-items:center;">
                <h4 class="page-title">T&S Solicitud de cambio o desarrollo de Sistema, Consulta General</h4>
            </div>
        </div>
        <div class="row animated fadeInUp">
            <div class="col-sm-12">
                <div class="white-box">
                    <label class="text-black" style="display:flex; justify-content: center;">Folio: </label>
                    <input id="selectFolio" class="form-control" name="folio" placeholder="Seleccione un folio">

                    <input readonly id="inputFolio" type="text" class="form-control" placeholder="" value=""
                        name="nomb">
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

                    <div id="divRechazo" class="col-md-12" style="display:flex; justify-content: center;">

                    </div>

                    <div class="form-horizontal form-material">
                        <div class="col-md-12">
                            <br>
                            <div class="label-inverse">
                                <label class="text-white">Datos del solicitante</label>
                            </div>
                        </div>
                        <br />
                        <div class="form-group">
                            <div class="col-md-6">
                                <label class="col-md-6">No Reloj:</label>
                                <input readonly id="inputReloj" type="text" class="form-control" placeholder="" value=""
                                    name="nomb">
                            </div>

                            <div class="col-md-6">
                                <label class="col-md-6">Fecha:</label>
                                <input readonly id="inputFecha" type="text" class="form-control" placeholder="" value=""
                                    name="correo">
                            </div>
                        </div>
                    </div>

                    <div class="form-horizontal form-material">
                        <div class="form-group">
                            <div class="col-md-6">
                                <label class="col-md-6">Nombre:</label>
                                <input readonly id="inputNombre" type="text" class="form-control" placeholder=""
                                    value="" name="nomb">
                            </div>

                            <div class="col-md-6">
                                <input readonly id="inputCorreo" type="text" class="form-control" placeholder=""
                                    value="" name="correo">
                            </div>

                            <div class="col-md-6">
                                <label class="col-md-6">Departamento:</label>
                                <input readonly id="inputDepartamento" type="text" class="form-control" placeholder=""
                                    value="" name="nomb">
                            </div>

                            <div class="col-md-6">
                                <label class="col-md-6">Encargado:</label>
                                <input readonly id="inputNombreEncargado" type="text" class="form-control"
                                    placeholder="" value="" name="nomb">
                            </div>
                        </div>

                        <div class="label-inverse">
                            <label class="text-white">Requerimientos</label>
                        </div>
                        <br />

                        <div id="RadioTipoSoli">
                            <div class="col-md-12">
                                <label class="col-md-2">Tipo de Solicitud:</label>
                                <div class="col-md-3">
                                    <input readonly id="inputTipoSolicitud" type="text" class="form-control"
                                        placeholder="" value="" name="nomb">
                                </div>

                                <label class="col-md-2">Tipo de Trabajo:</label>
                                <div class="col-md-3">
                                    <input readonly id="inputTipoTrabajo" type="text" class="form-control"
                                        placeholder="" value="" name="nomb">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="col-md-6">Nombre del Sistema:</label>
                                <input readonly id="inputNombreSistema" type="text" class="form-control" placeholder=""
                                    value="" name="nomb">
                            </div>

                            <div class="col-md-6">
                                <label class="col-md-6">Nombre del Modulo/Reporte:</label>
                                <input readonly id="inputNombreModRep" type="text" class="form-control" placeholder=""
                                    value="" name="nomb">
                            </div>

                            <div class="col-md-6">
                                <label class="col-md-6">Numero de Usuarios:</label>
                                <input readonly id="inputNumeroUsuarios" type="text" class="form-control" placeholder=""
                                    value="" name="nomb">
                            </div>

                            <div class="col-md-6">
                                <label class="col-md-6">Departamentos Involucrados:</label>
                                <input readonly id="inputDepartsInvol" type="text" class="form-control" placeholder=""
                                    value="" name="nomb" maxlength="50">
                            </div>

                            <label class="col-md-12">Requieren Capacitacion:</label>
                            <div class="form-group"
                                style="display:flex; justify-content:space-between;align-items:center;">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        <input readonly id="inputCapacitacion" type="text" class="form-control"
                                            placeholder="" value="" name="nomb">
                                    </div>
                                </div>
                            </div>

                            <div class="label-inverse">
                                <label class="text-white">Descripcion de la Solicitud</label>
                            </div>
                            <br />

                            <div class="col-md-24">
                                <input readonly id="inputDescripcion" type="text" class="form-control" maxlength="100"
                                    value="" name="nomb">
                            </div>
                            <br>

                            <div class="col-md-24" style="display:flex; justify-content: center;">
                                <input value="Ver Archivo" class="btn btn-info" onclick="seeFile()" id="fileInput">
                            </div>
                            <br>
                            <div class="label-inverse">
                                <label class="text-white">Retorno de inversión y disminución de headcount</label>
                            </div>
                            <br />

                            <div class="col-md-24" style="display:flex; justify-content: center;">
                                <div class="col-md-12">
                                    <div class="label-inverse">
                                        <label class="text-white">ROI</label>
                                    </div>
                                    <br>
                                    <div class="col-md-12">
                                        <label class="col-md-6">Equipo:</label>
                                        <input readonly id="inputEquipo" class="col-md-6" type="number"
                                            class="form-control" placeholder="USD $" value="" name="nomb">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="col-md-6">Software:</label>
                                        <input readonly id="inputSoftware" class="col-md-6" type="number"
                                            class="form-control" placeholder="USD $" value="" name="nomb">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="col-md-6">Salario:</label>
                                        <input readonly id="inputSalario" class="col-md-6" type="number"
                                            class="form-control" placeholder="USD $" value="" name="nomb">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="col-md-6">Ahorro:</label>
                                        <input readonly id="inputAhorro" class="col-md-6" type="number"
                                            class="form-control" placeholder="USD $" value="" name="nomb">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="col-md-6">ROI:</label>
                                        <input readonly id="inputROI" class="col-md-6" type="text" class="form-control"
                                            placeholder="0" value="" name="nomb">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="label-inverse">
                                        <label class="text-white">HEADCOUNT</label>
                                    </div>
                                    <br>
                                    <div class="col-md-12">
                                        <label class="col-md-6">Area/Departamento:</label>
                                        <input readonly id="inputAreaDepart" type="text" class="form-control"
                                            placeholder="" value="" name="nomb" maxlength="50">
                                    </div>

                                    <div class="col-md-12">
                                        <label class="col-md-4">Posicion</label>
                                        <label class="col-md-2">Directos</label>
                                        <label class="col-md-2">Indirectos</label>
                                        <label class="col-md-2">Admin.</label>
                                        <label class="col-md-2">Total</label>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="col-md-4">Antes</label>
                                        <input readonly id="inputAntesDir" class="col-md-2" type="number"
                                            class="form-control" placeholder="0" value="" name="nomb">
                                        <input readonly id="inputAntesInd" class="col-md-2" type="number"
                                            class="form-control" placeholder="0" value="" name="nomb">
                                        <input readonly id="inputAntesAdm" class="col-md-2" type="number"
                                            class="form-control" placeholder="0" value="" name="nomb">
                                        <input readonly id="inputAntesTotal" class="col-md-2" type="number"
                                            class="form-control" placeholder="0" value="" name="nomb">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="col-md-4">Despues</label>
                                        <input readonly id="inputDespDir" class="col-md-2" type="number"
                                            class="form-control" placeholder="0" value="" name="nomb">
                                        <input readonly id="inputDespInd" class="col-md-2" type="number"
                                            class="form-control" placeholder="0" value="" name="nomb">
                                        <input readonly id="inputDespAdm" class="col-md-2" type="number"
                                            class="form-control" placeholder="0" value="" name="nomb">
                                        <input readonly id="inputDespTotal" class="col-md-2" type="number"
                                            class="form-control" placeholder="0" value="" name="nomb">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="col-md-4">Diferencia</label>
                                        <input readonly id="inputDifeDir" class="col-md-2" type="number"
                                            class="form-control" placeholder="0" value="" name="nomb">
                                        <input readonly id="inputDifeInd" class="col-md-2" type="number"
                                            class="form-control" placeholder="0" value="" name="nomb">
                                        <input readonly id="inputDifeAdm" class="col-md-2" type="number"
                                            class="form-control" placeholder="0" value="" name="nomb">
                                        <input readonly id="inputDifeTotal" class="col-md-2" type="number"
                                            class="form-control" placeholder="0" value="" name="nomb">
                                    </div>
                                </div>
                            </div>

                            <br>

                            <div class="label-inverse">
                                <label class="text-white">Propuesta de Plan de Trabajo y Fechas de Entrega</label>
                            </div>

                            <!-- Proyecto Factible -->
                            <br>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Proyecto Factible:</label>
                                    <input readonly id="proyectoFactible" type="text" class="form-control"
                                        placeholder="-">
                                </div>
                                <div class="form-group">
                                    <!-- Razón -->
                                    <div class="col-md-6">
                                        <label for="razon">Razón:</label>
                                        <input readonly id="proyectoRazon" type="text" class="form-control"
                                            placeholder="-">
                                    </div>

                                    <!-- No De Ticket Asignado -->
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="ticketAsignado">No De Ticket Asignado:</label>
                                            <input readonly id="proyectoTicket" type="text" class="form-control"
                                                placeholder="-">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <!-- Duracion del Proyecto -->
                                    <div class="col-md-6">
                                        <label for="duracionProyecto">Duracion del Proyecto:</label>
                                        <input readonly id="proyectoDuracion" type="text" class="form-control"
                                            placeholder="-">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="inicio">Inicio:</label>
                                        <input readonly id="proyectoInicio" type="text" class="form-control"
                                            placeholder="-">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="fin">Fin:</label>
                                        <input readonly id="proyectoFin" type="text" class="form-control"
                                            placeholder="-">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <!-- Responsable -->
                                    <div class="col-md-6">
                                        <label for="noEmpleado">No. Empleado:</label>
                                        <input readonly id="proyectoNoEmpleado" type="text" class="form-control"
                                            placeholder="-">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="responsable">Responsable:</label>
                                        <input readonly id="proyectoEmpleado" type="text" class="form-control"
                                            placeholder="-">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <!-- Juntas Programadas -->
                                    <div class="col-md-12">
                                        <label>Juntas Programadas</label>
                                        <div>
                                            <input readonly id="proyectoJuntas" type="text" class="form-control"
                                                placeholder="-">
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
                                </div>
                            </div>
                            <br>
                            <br>
                            <!-- Recursos o herramientas especiales requeridos -->
                            <div class="form-group">
                                <label for="recursosEspeciales">Recursos o herramientas especiales requeridos:</label>
                                <input readonly id="proyectoRecursos" type="text" class="form-control" placeholder="-">
                            </div>

                            <div id="divProyectos">
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
                            <br>
                            <br>
                            <div class="col-md-12">
                                <div id="consultaGeneralBtnDiv" class="col-md-12" style="display:flex; justify-content: center;">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button id="btnGoTop" title="Ir arriba">&#8679;</button>
            <button id="btnGoBottom" title="Ir abajo">&#8681;</button>
        </div>

        <script src="plugins/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="js/solicitud_cambio_sistema_consultaGeneral.js"></script>
        <script src="./js/sweetalert2.all.js"></script>
        <?php include("footer.php");?>

</html>