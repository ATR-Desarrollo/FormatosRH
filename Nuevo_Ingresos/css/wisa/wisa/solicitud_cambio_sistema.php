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

<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12"
                style="display:flex; justify-content:space-between;align-items:center;">
                <h4 class="page-title">T&S Solicitud de cambio o desarrollo de Sistema</h4>
            </div>
        </div>
        <div class="row animated fadeInUp">
            <div class="col-sm-12">
                <div class="white-box">
                    <div class="form-horizontal form-material">
                        <div class="label-inverse">
                            <label for="inputReloj" class="text-white">Datos del solicitante</label>
                        </div>
                        <br />
                        <div class="form-group">
                            <label for="inputReloj" class="col-md-12">No. Reloj: </label>
                            <div class="col-md-4">
                                <input id="inputReloj" type="number" class="form-control" placeholder="" value=""
                                    name="NoReloj" autocomplete="off">
                            </div>
                            <div class="col-md-8">
                                <input id="btnConsultar" class="btn btn-info" type="submit" name="btnConsultar"
                                    value="Consultar" onclick="searchName()">
                            </div>
                        </div>
                    </div>

                    <div class="form-horizontal form-material">
                        <div class="form-group">
                            <div class="col-md-6">
                                <label for="inputNombreReloj" class="col-md-6">Nombre:</label>
                                <input readonly id="inputNombreReloj" type="text" class="form-control" placeholder=""
                                    value="" name="nomb" >
                            </div>

                            <div class="col-md-6">
                                <input readonly id="inputCorreo" type="text" class="form-control" placeholder="" value=""
                                    name="correo" >
                            </div>

                            <div class="col-md-6">
                                <label class="col-md-1">Fecha:</label>
                                <input readonly id="inputFecha" type="text" class="form-control" placeholder="" value="<?php echo date("Y-m-d") ?>"
                                    name="nomb" >
                            </div>

                            <div class="col-md-6">
                                <label class="col-md-6">Departamento:</label>
                                <select id="selectDpto" class="form-control" name="dpto">
                                    <option value="">Seleccione un Departamento</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="col-md-6">Encargado:</label>
                                <input readonly id="inputNombreEncargado" type="text" class="form-control"
                                    placeholder="" value="" name="nomb" >
                                
                            </div>
                        </div>

                        <div class="label-inverse">
                            <label class="text-white">Requerimientos</label>
                        </div>
                        <br />

                        <div id="RadioTipoSoli">
                            <label class="col-md-12">Tipo de Solicitud:</label>
                            <div class="form-group"
                                style="display:flex; justify-content:space-between;align-items:center;">
                                <label class="col-md-2 container">Sistema
                                    <input class="col-md-2" type="radio" name="tipo" checked value="Sistema">
                                </label>
                                <label class="col-md-2 container">Reporte
                                    <input class="col-md-2" type="radio" name="tipo" value="Reporte">
                                </label>
                                <label class="col-md-2 container">Modulo
                                    <input class="col-md-2" type="radio" name="tipo" value="Modulo">
                                </label>
                                <label class="col-md-2 container">Consulta
                                    <input class="col-md-2" type="radio" name="tipo" value="Consulta">
                                </label>
                            </div>

                            <label class="col-md-12">Tipo de Trabajo:</label>
                            <div class="form-group"
                                style="display:flex; justify-content:space-between;align-items:center;">
                                <label class="col-md-2 container">Nuevo
                                    <input class="col-md-2" type="radio" name="tipo2" checked value="Nuevo">
                                </label>
                                <label class="col-md-2 container">Cambio
                                    <input class="col-md-2" type="radio" name="tipo2" value="Cambio">
                                </label>
                            </div>

                            <div class="col-md-6">
                                <label class="col-md-6">Nombre del Sistema:</label>
                                <input id="inputNombreSistema" type="text" class="form-control" placeholder="" value=""
                                    name="nomb" maxlength="100" autocomplete="off">
                            </div>

                            <div class="col-md-6">
                                <label class="col-md-6">Nombre del Modulo/Reporte:</label>
                                <input id="inputNombreModRep" type="text" class="form-control" placeholder="" value=""
                                    name="nomb" maxlength="100" autocomplete="off">
                            </div>

                            <div class="col-md-6">
                                <label class="col-md-6">Numero de Usuarios:</label>
                                <input id="inputNumeroUsuarios" type="text" class="form-control" placeholder=""
                                    value="" name="nomb" autocomplete="off">
                            </div>

                            <div class="col-md-6">
                                <label class="col-md-6">Departamentos Involucrados:</label>
                                <input id="inputDepartsInvol" type="text" class="form-control" placeholder="" value=""
                                    name="nomb" maxlength="50" autocomplete="off">
                            </div>

                            <label class="col-md-12">Requieren Capacitacion:</label>
                            <div class="form-group"
                                style="display:flex; justify-content:space-between;align-items:center;">
                                <label class="col-md-2 container">Si
                                    <input class="col-md-2" type="radio" name="tipo3" checked value="1">
                                </label>
                                <label class="col-md-2 container">No
                                    <input class="col-md-2" type="radio" name="tipo3" value="0">
                                </label>
                            </div>

                            <div class="label-inverse">
                                <label class="text-white">Descripcion de la Solicitud</label>
                            </div>
                            <br />

                            <div class="col-md-24">
                                <textarea id="inputDescripcion" class="form-control" maxlength="500" name="nomb" rows="4" autocomplete="off"></textarea>
                                <span class="pull-right label label-default" id="count_message"></span>
                            </div>

                            <br>
                            <div class="col-md-24" style="display:flex; flex-direction:column; align-items: center;">
                                <label for="fileInput" class="col-md-12" style="margin-bottom:8px; text-align:center; width:100%;">Adjuntar archivo de justificación para la solicitud (excel, word, powepoint, pdf o imagen)</label>
                                <input class="btn btn-info" type="file" id="fileInput">
                            </div>
                            <br>
                            <div class="label-inverse">
                                <label class="text-white">Retorno de inversión y disminución de headcount (Solo llenar si el tipo de trabajo es Nuevo)</label>
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
                                        <input id="inputEquipo" class="col-md-6" type="number" class="form-control"
                                            placeholder="USD $" value="" name="ROI" autocomplete="off">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="col-md-6">Software:</label>
                                        <input id="inputSoftware" class="col-md-6" type="number" class="form-control"
                                            placeholder="USD $" value="" name="ROI" autocomplete="off">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="col-md-6">Salario:</label>
                                        <input id="inputSalario" class="col-md-6" type="number" class="form-control"
                                            placeholder="USD $" value="" name="ROI" autocomplete="off">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="col-md-6">Ahorro:</label>
                                        <input id="inputAhorro" class="col-md-6" type="number" class="form-control"
                                            placeholder="USD $" value="" name="ROI" autocomplete="off">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="col-md-6">ROI:</label>
                                        <input readonly id="inputROI" class="col-md-6" type="text" class="form-control"
                                            placeholder="0" value="0" name="ROI">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="label-inverse">
                                        <label class="text-white">HEADCOUNT</label>
                                    </div>
                                    <br>
                                    <div class="col-md-12">
                                        <label class="col-md-6">Area/Departamento:</label>
                                        <input id="inputAreaDepart" type="text" class="form-control" placeholder=""
                                            value="" name="nomb" maxlength="50" autocomplete="off">
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
                                        <input id="inputAntesDir" class="col-md-2" type="number" name="HEADCOUNT" autocomplete="off">
                                        <input id="inputAntesInd" class="col-md-2" type="number" name="HEADCOUNT" autocomplete="off">
                                        <input id="inputAntesAdm" class="col-md-2" type="number" name="HEADCOUNT" autocomplete="off">
                                        <input id="inputAntesTotal" class="col-md-2" type="number" name="HEADCOUNT" autocomplete="off">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="col-md-4">Despues</label>
                                        <input id="inputDespDir" class="col-md-2" type="number" class="form-control"
                                            value="" name="HEADCOUNT" autocomplete="off">
                                        <input id="inputDespInd" class="col-md-2" type="number" class="form-control"
                                            value="" name="HEADCOUNT" autocomplete="off">
                                        <input id="inputDespAdm" class="col-md-2" type="number" class="form-control"
                                            value="" name="HEADCOUNT" autocomplete="off">
                                        <input id="inputDespTotal" class="col-md-2" type="number" class="form-control"
                                            value="" name="HEADCOUNT" autocomplete="off">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="col-md-4">Diferencia</label>
                                        <input id="inputDifeDir" class="col-md-2" type="number" class="form-control"
                                            placeholder="-" value="" name="HEADCOUNT" autocomplete="off" readonly>
                                        <input id="inputDifeInd" class="col-md-2" type="number" class="form-control"
                                            placeholder="-" value="" name="HEADCOUNT" autocomplete="off" readonly>
                                        <input id="inputDifeAdm" class="col-md-2" type="number" class="form-control"
                                            placeholder="-" value="" name="HEADCOUNT" autocomplete="off" readonly>
                                        <input id="inputDifeTotal" class="col-md-2" type="number" class="form-control"
                                            placeholder="-" value="" name="HEADCOUNT" autocomplete="off" readonly>
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
                        <div class="col-md-12" style="display:flex; justify-content: center;">
                            <input id="btnSolicitar" class="btn btn-info" type="submit" name="btnSolicitar"
                                value="Registrar solicitud" onclick="btnSolicitar()">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="js/solicitud_cambio_sistema.js"></script>
    <script src="./js/sweetalert2.all.js"></script>
    <?php include("footer.php");?>

</html>