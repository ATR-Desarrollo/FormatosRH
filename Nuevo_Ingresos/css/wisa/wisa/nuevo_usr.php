<?php
//
session_start();

 

include("conexion.php");

if (isset( $_POST["NoReloj"])) { // <= true
  $NoCtr =  $_POST["NoReloj"];
 

$sql =" select * from  Usuarios where Noreloj=".$NoCtr;

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
  window.location.href="nuevo_usr.php";
  </script>';           }
else    {
if($row_count > 0)        {  //si existe el usuario

  echo'<script type="text/javascript">
  alert("Usuario ya existe, favor de verificar");
  window.location.href="login.php";
  </script>'; 
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

 
<!DOCTYPE html>
<html lang="es">

<head> 
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
 
  
  <!-- Left navbar-header end -->
  <!-- Page Content -->
  <div id="page-wrapper">
    <div class="container-fluid">
      <div class="row bg-title">
        <div class="col-lg-6 col-md-6 col-sm-10 col-xs-12">
          <h4 class="page-title">Evaluaciones ATR - Registrar Usuario</h4>
        </div>
         
        <!-- /.col-lg-12 -->
      </div>
      
     <div class="row animated fadeInUp">
        <div class="col-sm-12">
          <div class="white-box">
         
          <form class="form-horizontal form-material" action="nuevo_usr.php" method="post" > 
           

           <div class="form-group">
                 <label class="col-md-12">No. Reloj:   </label>
          <div class="col-md-4">
            <input type="text" class="form-control" placeholder="" value="<?php if (isset( $_POST["NoReloj"])) { echo $NoCtr ; }?>" name="NoReloj">  </div>
              <div class="col-md-8">   <button type="submit" class="btn btn-success">Consultar</button></div> 
                  
        </div>
        </form>


        <form class="form-horizontal form-material" action="nuevo_usr_add.php" method="post" >         
          
            <div class="form-group">
                 <label class="col-md-6">Nombre:</label>                 
                 <label class="col-md-6">Correo:</label>                 
          
          <div class="col-md-6">
            <input type="text" class="form-control" placeholder="" value="<?php if (isset( $_POST["NoReloj"])) { echo utf8_encode($row['Nombre']);} ?>" name="nomb"> 
          </div>
          <div class="col-md-6">
            <input type="text" class="form-control" placeholder="" value="<?php if (isset( $_POST["NoReloj"])) { echo $row['Correo'] ;} ?>" name="corr" required> 
             
            <input type="hidden" class="form-control" placeholder="" value="<?php if (isset( $_POST["NoReloj"])) { echo $NoCtr;} ?>" name="nume" style="visible:false;">
          </div>
        </div>

        <div class="form-group">
                 <label class="col-md-4">Planta:</label>
                 <label class="col-md-8">Departamento:</label>
                
                          <div class="col-md-4">
            <input type="text" class="form-control" placeholder=""  value="<?php if (isset( $_POST["NoReloj"])) { echo $row['Planta'] ;} ?>"  required name="plan">  
          </div>
           
          <div class="col-md-4">
            <input type="text" class="form-control" placeholder=""   value="<?php if (isset( $_POST["NoReloj"])) { echo $row['Departamento'] ;} ?>" required name="depa">  
          </div>
          <div class="col-md-8">
          <label  > </label>
          </div>
           
        </div>

        <div class="form-group">
                 <label class="col-md-4">Usuario:</label>
                 <label class="col-md-4">Password:</label>
                 <label class="col-md-4">Confirme password:</label>
                          <div class="col-md-4">
            <input type="text" class="form-control" placeholder=""  value="<?php if (isset( $_POST["NoReloj"])) { echo $row['Usuario'] ;} ?>"  required name="usua">  
          </div>
           
          <div class="col-md-4">
            <input type="password" class="form-control" placeholder=""   value="<?php if (isset( $_POST["NoReloj"])) { echo $row['Password'] ;} ?>" required name="pass">  
          </div>
           <div class="col-md-4">
            <input type="password" class="form-control" placeholder=""  value="<?php if (isset( $_POST["NoReloj"])) { echo $row['Password'] ;} ?>"  required  name="pas2">  
          </div>
        </div>

        <input type="hidden" class="form-control" placeholder="" value="0" name="tipo" > 

         
        <div class="form-group">
                  
                   
          
          
        </div>
        
    
<br>
     <button type="submit" class="btn btn-info waves-effect waves-light m-t-10">Registrar</button>
<br>
   
 
   </div> 
</form>
<br>
</div>

<a href="login.php"  class="btn btn-default m-t-10">Regresar</a> 

</div>
  
        <br>
    <footer class="footer text-center"> <?php  echo date("Y"); ?> &copy; ATR AutoSistemas de Torreon SA de CV </footer>


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
<script src="plugins/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<script>
// Date Picker
    jQuery('#datepicker-autoclose').datepicker({
        autoclose: true,
        todayHighlight: true
      });
// Daterange 

jQuery('#date-range').datepicker({
        toggleActive: true
      });

</script>


</body>

</html>
