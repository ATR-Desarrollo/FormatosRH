<?php
//
session_start();

include("conexion.php");
$NoCtr = "";
$name = "";
$dpto = "";
$usrIP = "";

$planta = "SAU";
$dptos = "Control de Produccion";
$area = "Etiquetas";
$equipo = "";
$motivo = "";
$detail = "";

$Activo = '';

if (isset( $_POST["NoReloj"])) {  
    $NoCtr =  $_POST["NoReloj"]; 
    $sql = " [SP_CONSULTA_DATOS_ASOCIADO] '".$NoCtr."'"; 
    $params=array();
    $options=array("Scrollable" => SQLSRV_CURSOR_KEYSET );
    $stmt = sqlsrv_query( $conn, $sql , $params, $options );
    $row_count = sqlsrv_num_rows( $stmt );
    $row=sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
    $name = $row['Nombre'] ;
    $dpto = $row['Departamento'] ;    
}
else
{
    $Activo = 'Disabled';
}



?>
<script>
function agregar() {
    $('#formulario').modal('show');
}

function modificar(nop) {
    $('#formulario' + nop).modal('show');
}
</script>

<?php include "header.php";?>
<?php include "navbar.php";?>

<!DOCTYPE html>
<html lang="en">

<!-- Left navbar-header end -->
<!-- Page Content -->
<style>
    td{padding: 5;}
</style>
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-10 col-xs-12">
                <h4 class="page-title">Listado de lineas | Bateria Ensamble </h4>
            </div>

            <!-- /.col-lg-12 -->
        </div>        

        <div class="row animated fadeIn">
            <div class="col-sm-12">
                <div class="white-box">

                    <!-- FORMULARIO SOLICITUD -->
                    <!-- <form class="form-horizontal form-material" action="ticket_add.php" method="post"> -->

                    <!-- <div class="label-inverse">
                        <label class="text-white">Detalle de la linea</label> </br>
                    </div> -->
                    <input name="No_Reloj" value="<?php echo $NoCtr;?>" hidden>
                    <input name="name" value="<?php echo $name;?>" hidden>
                    <input name="dpto" value="<?php echo $dpto;?>" hidden>
                    <input name="ip" value="<?php echo $_SERVER['REMOTE_ADDR'];?>" hidden>

                    <!-- <br>                          -->
                    <div class="form-group">

                        <div class="col-md-12">
                            <label class="col-md-1">Linea:</label>  
                            <select class="col-md-4" name="selLinea" id="selLinea"></select>
                            <label class="col-md-1"></label> 
                            <button class="btn btn-info waves-effect waves-light m-t-10" onclick="agregaLinea()"> +</button>
                        </div>  
                        
                        <!-- Button trigger modal -->
                        
                    </div>
                    <br><br><br><br>
                    <table style="width: 100%">
                        <thead>
                            <tr style="text-align: center">
                                <td style="text-align: center; border-bottom: 1px solid #ddd; width: 250px;">No Linea</td>
                                <td style="text-align: center; border-bottom: 1px solid #ddd; width: 250px;">Nombre</td>
                                <td style="text-align: center; border-bottom: 1px solid #ddd; width: 250px;">Accion</td>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



        <br>
        <footer class="footer text-center"> <?php  echo date("Y"); ?> &copy; ATR AutoSistemas de Torreon SA de CV
        </footer>


        <!-- /#wrapper -->

        <!-- Bootstrap Core JavaScript -->
        <script src="bootstrap/dist/js/bootstrap.min.js"></script>
        <!-- Menu Plugin JavaScript -->
        <script src="plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
        <!--slimscroll JavaScript -->
        <script src="js/jquery.slimscroll.js"></script>
        <!--Wave Effects -->
        <script src="js/waves.js"></script>
        <!-- Custom Theme JavaScript -->
        <script src="js/custom.js"></script>
        <!-- Date Picker Plugin JavaScript -->
        <script src="plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
        <!-- Date range Plugin JavaScript -->
        <script src="plugins/bower_components/timepicker/bootstrap-timepicker.min.js"></script>
        <script src="./js/scripts/batlines.js"></script>
        <!--Style Switcher -->
        </body>

</html>