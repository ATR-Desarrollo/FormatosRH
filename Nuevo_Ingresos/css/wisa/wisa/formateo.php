<?php
//
session_start();

include("conexion.php");
$NR = "";
$nm = "";
$do = "";
$ip = "";
$pl = "";
$dp = "";
$ar = "";
$mt = "";
$Up = "";
$pw = "";
$rr = "";
$rt = "";
$ml = '';


if (isset( $_POST["NoReloj"])) { // <= true
  $NR =  $_POST["NoReloj"];
 
$sql =" select * from  Usuarios where noreloj=".$NR;
  
  $params=array();
  $options=array("Scrollable" => SQLSRV_CURSOR_KEYSET );

 $stmt = sqlsrv_query( $conn, $sql , $params, $options );
 $row_count = sqlsrv_num_rows( $stmt );
 $row=sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);

 if ($row_count === false) { // SI NO EXISTE EL no ctrl, MANDA MENSAJE DE ERROR             
  echo'<script type="text/javascript">
  alert("Error al realizar consulta");
  window.location.href="formateo.php";
  </script>';           }
else    {
if($row_count > 0)        
{  //si existe el usuario   
}
  else //No existe el Usuario
  {
  $sql = " [SP_SFIS_CONSULTA_NORELOJ] '".$NR."'"; 
  $params=array();
  $options=array("Scrollable" => SQLSRV_CURSOR_KEYSET );
  $stmt = sqlsrv_query( $conn, $sql , $params, $options );
  $row_count = sqlsrv_num_rows( $stmt );
  $row=sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);

  $nm = $row['Nombre'] ;  
  $do = $row['Departamento'];  
  $pl = $row['Planta'];
  $Up = $row['Usuario'];
  $pw = $row['Password'];
  $ml = $row['Correo'];
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
                <h4 class="page-title">W IS A - Solicitud de formateo de equipo de computo</h4>
            </div>

            <!-- /.col-lg-12 -->
        </div>

        <div class="row animated fadeInUp">
            <div class="col-sm-12">
                <div class="white-box">

                    <form class="form-horizontal form-material" action="formateo.php" method="post">


                        <div class="form-group">
                            <label class="col-md-12">No. Reloj: </label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" placeholder=""
                                    value="<?php echo $NR; ?>" name="NoReloj">
                            </div>
                            <div class="col-md-8"> <button type="submit" class="btn btn-success">Consultar</button>
                            </div>
                        </div>
                    </form>


                    <form class="form-horizontal form-material" action="formateo_add.php" method="post">

                        <div class="form-group">
                            <div class="col-md-4">
                              <label class="col-md-12">Nombre:</label>
                              <input type="text" class="form-control" placeholder="" value="<?php echo $nm; ?>" required name="name">
                            </div>
                            <div class="col-md-4">
                              <label class="col-md-12">Planta:</label>
                              <input type="text" class="form-control" placeholder="" value="<?php echo $pl;  ?>" required name="planta">
                            </div>
                            <div class="col-md-4">
                              <label class="col-md-12">Departamento:</label>
                              <input type="text" class="form-control" placeholder="" value="<?php echo $do;  ?>" required name="dpto">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-4">
                              <label class="col-md-12">Usuario:</label>
                              <input type="text" class="form-control" placeholder="" value="<?php echo $Up;  ?>" required name="Usuario">
                              <input type="hidden" class="form-control" placeholder="" value="<?php echo $NR; ?>" name="No_Reloj" style="visible:false;">
                              <input name="ip" value="<?php echo $_SERVER['REMOTE_ADDR'];?>" hidden>
                            </div>
                            <!--
                            <div class="col-md-3">
                              <label class="col-md-12">Password:</label>
                              <input type="password" class="form-control" placeholder="" value="<?php echo $pw;  ?>" name="Passwor">
                            </div>
-->
                            <div class="col-md-8">
                              <label class="col-md-12">Correo:</label>
                                <input type="text" class="form-control" placeholder="" value="<?php echo $ml; ?>" name="mail">                                
                            </div>

                        </div>
                        <div class="form-group">                            
                            <div class="col-md-2">
                            <label class="col-md-12">Requiere Respaldo:</label>
                            <select class="col-md-12" name="ReqResp">
                                <option value="2" <?php if ( $rt != '') {  if  ( $rt =='2') echo 'selected="selected"';} ?>> No </option>
                                <option value="1" <?php if ( $rt != '') {  if  ( $rt =='1') echo 'selected="selected"';} ?>> Si </option>
                            </select>
                            </div>
                            <div class="col-md-10">
                              <label class="col-md-12">Ruta de Respaldo:</label>
                                <input type="text" class="form-control" placeholder="" value="<?php echo $rt  ?>" name="RutResp">
                            </div>                            
                        </div>
                        <div class="form-group">                            
                            <div class="col-md-12">
                              <label class="col-md-12">Motivo:</label>
                              <input type="text" class="form-control" placeholder="" value="<?php echo $mt ; ?>"name="motivo">
                            </div>
                        </div>

                        <br>
                        <button type="submit" class="btn btn-info waves-effect waves-light m-t-10">Registrar</button>
                        <br>


                </div>
                </form>
                <br>
            </div>


        </div>

        <br>
        <?php include("footer.php");?>          
        </body>

</html>