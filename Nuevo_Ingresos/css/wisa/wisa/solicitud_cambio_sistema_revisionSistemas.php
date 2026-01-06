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

<!-- Modal AGREGAR-->
<meta charset="UTF-8">

<div>
    <input type="hidden" id="pageID" value="revisionSistemas">
</div>
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12"
                style="display:flex; justify-content:space-between;align-items:center;">
                <h4 class="page-title">T&S Solicitud de cambio o desarrollo de Sistema Revisión Sistemas</h4>
            </div>
        </div>
        <div class="row animated fadeInUp">
            <div class="col-sm-12">
                <div class="white-box">
                    <label class="text-black" style="display:flex; justify-content: center;">Folio: </label>
                    <input readonly id="inputFolio" type="text" class="form-control" placeholder="" value=""
                        name="nomb">
                    <br>
                    <div class="form-horizontal form-material">
                        <div class="label-inverse">
                            <label class="text-white">Datos del solicitante</label>
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
                        </div>
                    </div>
                    <br>
                    <div style="display:flex; justify-content: center;">
                        <table>
                            <thead>
                                <tr>
                                    <th class="text-white" scope="col">Revision</th>
                                    <th class="text-white" scope="col">Fecha Efectiva</th>
                                    <th class="text-white" scope="col">No. Control</th>
                                    <th class="text-white" scope="col">Generado Por:</th>
                                    <th class="text-white" scope="col">Aprobado Por:</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">0</th>
                                    <td>8/27/2021</td>
                                    <td>IS-004FS-01</td>
                                    <td>Ing. Luis Villalba</td>
                                    <td>Ing. Carlos Salazar</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <div class="col-md-12">
                        <div class="col-md-8">
                            <input id="btnAutorizar" class="btn btn-info" type="submit" name="btnSolicitar"
                                value="Autorizar" onclick="btnAutorizarSistemas()">
                        </div>
                        <div class="col-md-4" style="display:flex; justify-content: center;">
                            <input id="btnRechazar" class="btn btn-info" type="submit" name="btnSolicitar"
                                value="Rechazar" onclick="openModal()">
                        </div>
                    </div>
                    <!-- Modal Structure -->
                    <div class="modal" id="myModal">
                        <div class="modal-content">
                            <h2>Motivo de Rechazo</h2>
                            <input id="inputRechazoMotivo" type="text" class="form-control" placeholder="Escriba el motivo de Rechazo:">
                            <br>
                            <button id="closeModal">Cerrar</button>
                            
                            <button onclick="btnRechazarSistemas()">Confirmar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="js/solicitud_cambio_sistema_revision.js"></script>
    <script src="./js/sweetalert2.all.js"></script>
    <?php include("footer.php");?>

</html>