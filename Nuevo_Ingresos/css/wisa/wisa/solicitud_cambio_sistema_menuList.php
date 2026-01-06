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
input[type="button"] {
    width: 600px;
    height: 50px;
    font-size: 16px;
    padding: 10px;
    text-align: center;
    border-radius: 5px;
    background-color: #007bff;
    color: white;
    border: none;
    cursor: pointer;
}

input[type="button"]:hover {
    background-color: #0056b3;
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
    bottom: 80px;
    right: 32px;
}
#btnGoBottom {
    bottom: 16px;
    right: 32px;
}
</style>

<!-- Modal AGREGAR-->
<meta charset="UTF-8">

<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12"
                style="display:flex; justify-content:space-between;align-items:center;">
                <h4 class="page-title">Consulta de Solicitud de cambio o desarrollo de Sistema</h4>
            </div>
        </div>
        <div class="row animated fadeInUp">
            <div class="col-sm-12">
                <div class="white-box col-sm-12">
                    <div class="form-horizontal form-material">
                        <div class="col-md-12">
                            <div class="col-md-5" style="display:flex; justify-content: center;">
                                <select id="selectOption" class="form-control">
                                    <option value="0">Selecciona una Búsqueda</option>
                                    <option value="1">Todo</option>
                                    <option value="2">Folio</option>
                                    <option value="3">Nombre de Sistema</option>
                                    <option value="4">Departamento</option>
                                    <option value="5">Fecha</option>
                                    <option value="6">Tipo de Trabajo</option>
                                    <option value="7">Estatus</option>
                                    <option value="8">Urgencia</option>
                                    <option value="9">Numero de Reloj</option>
                                </select>
                            </div>

                            <div id="divCampos" class="col-md-5" style="display:flex; justify-content: center;">

                            </div>
                        </div>

                        <div class="col-md-12">
                            <br>
                            <div class="col-md-12" style="display:flex; justify-content: center;">
                                <div id="divFolio" style="width:100%;">
                                    <div style="overflow-x:auto">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Folio</th>
                                                    <th>Fecha</th>
                                                    <th>Departamento</th>
                                                    <th>Tipo Solicitud</th>
                                                    <th>Tipo Trabajo</th>
                                                    <th>Nombre Sistema</th>
                                                    <th>Estatus</th>
                                                    <th>Urgencia</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbodydivFolios"></tbody>
                                        </table>
                                    </div>
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
        <script src="js/solicitud_cambio_sistema_list.js"></script>
        <script src="./js/sweetalert2.all.js"></script>
        <?php include("footer.php");?>

</html>