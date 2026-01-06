<?php
//
session_start();

 

include("conexion.php");

if (isset( $_POST["NoReloj"])) { // <= true
  $NoCtr =  $_POST["NoReloj"];
 

$sql =" select * from  Usuarios where noreloj=".$NoCtr;

 // $sql = " select prettyname from vtressatrsau.sumitomo.dbo.colabora where cb_activo = 'S'  AND cb_codigo = ".$NoCtr;

  // echo'<script type="text/javascript">
  // alert("'.$sql.'");
   
  // </script>';    
  $params=array();
  $options=array("Scrollable" => SQLSRV_CURSOR_KEYSET );

 $stmt = sqlsrv_query( $conn, $sql , $params, $options );
 $row_count = sqlsrv_num_rows( $stmt );
 $row=sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);



 if ($row_count === false) { // SI NO EXISTE EL no ctrl, MANDA MENSAJE DE ERROR             
  echo'<script type="text/javascript">
  alert("Error al realizar consulta");
  window.location.href="usuarios.php";
  </script>';           }
else    {
if($row_count > 0)        {  //si existe el usuario

   
}
  else
  {
      $sql = " [SP_CONSULTA_DATOS_ASOCIADO] '".$NoCtr."'";
 
  $params=array();
  $options=array("Scrollable" => SQLSRV_CURSOR_KEYSET );

 $stmt = sqlsrv_query( $conn, $sql , $params, $options );
 $row_count = sqlsrv_num_rows( $stmt );
 $row=sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
   }


}




}

?>

<?php include "header.php";?>
<?php include "navbar.php";?>


<!DOCTYPE html>
<html lang="en">




<!-- Left navbar-header end -->
<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-10 col-xs-12">
                <h4 class="page-title">Evaluaciones ATR - Administracion Usuarios</h4>
            </div>

            <!-- /.col-lg-12 -->
        </div>


        <div class="row animated fadeIn">
            <div class="col-sm-12">
                <div class="white-box">
                    
                </div>
            </div>
        </div>



        <footer class="footer text-center"> <?php  echo date("Y"); ?> &copy; ATR AutoSistemas de Torreon SA de CV
        </footer>
        <!-- Bootstrap Core JavaScript -->
        <script src="bootstrap/dist/js/bootstrap.min.js"></script>
        <!-- Menu Plugin JavaScript -->
        <script src="plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
        <!-- Custom Theme JavaScript -->
        <script src="js/custom.js"></script>
        </body>
</html>