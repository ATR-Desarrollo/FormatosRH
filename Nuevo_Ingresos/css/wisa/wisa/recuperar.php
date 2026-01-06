<?php
//
session_start();
$NoContol = "";
   include("conexion.php");
$conn = sqlsrv_connect( $serverName, $connectionInfo);
 
if(!empty($_GET['u'])){$Usuario = $_GET['u'];}else{$Usuario = "";}
if(!empty($_GET['t'])){$Tipo = $_GET['t'];}else{$Tipo = "";}

$Usuario = $_GET['u'];
$Tipo = $_GET['t'];
 
 
if ($Tipo == '0')//CONSULTA
{
  
  $sql = "SELECT * FROM USUARIOS  where USUARIO = '$Usuario'";
  $params=array();
  $options=array("Scrollable" => SQLSRV_CURSOR_KEYSET );

 $stmt = sqlsrv_query( $conn, $sql , $params, $options );
 $row_count = sqlsrv_num_rows( $stmt );
 $row=sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
}
else{ //ACTUALIZA

  if(!empty($_POST))
{
   
  $pass1=$_POST['pass'];
  $pass2=$_POST['pas2'];

  if ($pass1 == $pass2 )
  {

    $sql = "update USUARIOS set password =?  where usuario = ? ";
    $proc_result = sqlsrv_prepare($conn, $sql,array(&$pass1,&$Usuario));
  //  $proc_result = sqlsrv_prepare($conn, $sql,array(&$NoCtr,&$NoPar,&$HBS,&$UsrId,&$InptV,&$InptT,&$CrosT));
    sqlsrv_execute($proc_result);
    echo'<script type="text/javascript">
    alert("Password actualizado");
    window.location.href="login.php";
    </script>';
  }
  else
  {
               echo'<script type="text/javascript">
                    alert("Password no coinciden favor de revisar");
                    window.location.href="recuperar.php?u='.$Usuario.'";
                    </script>';
  }
}
}

?>

<?php include "header.php";?>

<div id='contenido'>
    <!-- COMIENZA CONTENIDO -->

    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">Recuperar contraseña</h4>
                </div>

                <!-- /.col-lg-12 -->
            </div>
            <!-- .row -->
            <div class="row animated fadeInUp">
                <div class="col-sm-12">
                    <div class="white-box">
                        <form class="form-horizontal form-material"
                            action="recuperar.php?u=<?php  echo $Usuario ;  ?>&t=1" method="post"
                            onKeyPress="return disableEnterKey(event)">


                            <div class="form-group">
                                <label class="col-md-2">No de reloj : </label>
                                <div class="col-md-2">
                                    <input type="text" class="form-control" placeholder=""
                                        value="<?php  echo $row['No_Empleado'] ;  ?>" name="nore">
                                </div>


                                <label class="col-md-1">Nombre : </label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" placeholder=""
                                        value="<?php   echo $row['Nombre'] ;  ?>" name="nomb">
                                </div>


                                <label class="col-md-2">Usuario: </label>
                                <div class="col-md-2">
                                    <input type="text" class="form-control" placeholder=""
                                        value="<?php   echo $row['Usuario'] ; ?>" name="usua">
                                </div>

                            </div>

                            <div class="form-group">
                                <label class="col-md-2">Nueva contraseña: </label>
                                <div class="col-md-2">
                                    <input type="password" class="form-control" placeholder="" value="" name="pass"
                                        required>
                                </div>

                                <label class="col-md-2">Confirmar contraseña: </label>
                                <div class="col-md-2">
                                    <input type="password" class="form-control" placeholder="" value="" name="pas2"
                                        required>
                                </div>



                            </div>
                            <div class="col-md-3"> <button type="submit" class="btn btn-success">Actualizar</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div>


        <!-- TERMINA CONTENIDO -->
        <?php include "footer.php";?>
        </body>

        </html>