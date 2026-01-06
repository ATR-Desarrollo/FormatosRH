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
    // alert('no tema: '+nop);
    $('#formulario' + nop).modal('show');
}
</script>


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
                <h4 class="page-title">TICKET SOPORTE DE SISTEMAS ATR </h4>
            </div>

            <!-- /.col-lg-12 -->
        </div>        

        <div class="row animated fadeIn">
            <div class="col-sm-12">
                <div class="white-box">

                    <!-- FORMULARIO SOLICITANTE -->
                    <form class="form-horizontal form-material" action="ticket.php" method="post">
                        <div class="label-inverse">
                            <label class="text-white">Datos del usuario</label>
                        </div>
                        <div class="form-group">                          
                        <br>
                          <div class="col-md-3">
                              <label class="col-md-12">No. Reloj: <button type="submit" class="link link">Consultar</button></label>
                              <div class="col-md-12">
                                  <input type="text" class="form-control" placeholder=""
                                      value="<?php echo $NoCtr ; ?>" name="NoReloj">
                              </div>                                                          
                          </div>
                          <div class="col-md-5">
                              <label class="col-md-12">Nombre:</label>
                              <div class="col-md-12">
                                  <input type="text" class="form-control" placeholder=""
                                      value="<?php  echo $name ?>"  disabled>
                              </div>                              
                          </div>                              
                          <div class="col-md-4">
                              <label class="col-md-12">Departamento:</label>  
                              <div class="col-md-12">
                                  <input type="text" class="form-control" placeholder=""
                                      value="<?php echo $dpto ?>"  disabled>
                              </div>
                          </div>   
                        </div>
                    </form><!-- Termina FORMULARIO SOLICITANTE -->

                    <!-- FORMULARIO SOLICITUD -->
                    <form class="form-horizontal form-material" action="ticket_add.php" method="post">

                        <div class="label-inverse">
                            <label class="text-white">Detalle de la Solicitud</label> </br>
                        </div>
                        <input name="No_Reloj" value="<?php echo $NoCtr;?>" hidden>
                        <input name="name" value="<?php echo $name;?>" hidden>
                        <input name="dpto" value="<?php echo $dpto;?>" hidden>
                        <input name="ip" value="<?php echo $_SERVER['REMOTE_ADDR'];?>" hidden>



                        <br>                         
                        <div class="form-group">

                            <div class="col-md-4">
                                <label class="col-md-12">Planta:</label>  
                                <select class="col-md-12" name="planta"  id="planta" <?php echo $Activo;?>>
                                <?php                    
                                $sql2 = "SP_SSIS_PLANTAS";
                                $proc_result2 = sqlsrv_prepare($conn, $sql2);
                                sqlsrv_execute($proc_result2);
                                  while($row3=sqlsrv_fetch_array( $proc_result2, SQLSRV_FETCH_ASSOC)) 
                                  {
                                    if ($row3['Planta'] == $planta ){
                                      $select = 'selected';
                                    }
                                    else
                                    {
                                      $select = '';
                                    }
                                ?>                
                                <option value="<?php echo $row3['Planta']; ?>" <?php echo $select;?>  > <?php echo $row3['Planta']; ?> </option>
                                <?php 
                                  } ?>
                                </select>     
                                <br>
                            </div>   
                            <div class="col-md-4">
                                <label class="col-md-12">Departamento:</label>  
                                <select class="col-md-12" name="dptos"  id="dptos" <?php echo $Activo;?>>
                                <?php                    
                                $sql2 = "SP_SSIS_DEPARTAMENTOS";
                                $proc_result2 = sqlsrv_prepare($conn, $sql2);
                                sqlsrv_execute($proc_result2);
                                  while($row3=sqlsrv_fetch_array( $proc_result2, SQLSRV_FETCH_ASSOC)) 
                                  {
                                    if ($row3['Departamento'] === $dptos ){
                                      $select = 'selected';
                                    }
                                    else
                                    {
                                      $select = '';
                                    }
                                ?>                
                                <option value="<?php echo $row3['Departamento']; ?>" <?php echo $select;?>> <?php echo $row3['Departamento']; ?> </option>
                                <?php 
                                  } ?>
                                </select>   
                                <br>  
                            </div>   

                            <div class="col-md-4">
                                <label class="col-md-12">Area:</label>  
                                <select class="col-md-12" name="area" <?php echo $Activo;?>>
                                <?php                    
                                $sql2 = "SP_SSIS_AREAS 'Control de Produccion'";
                                $proc_result2 = sqlsrv_prepare($conn, $sql2);
                                sqlsrv_execute($proc_result2);
                                  while($row3=sqlsrv_fetch_array( $proc_result2, SQLSRV_FETCH_ASSOC)) 
                                  {
                                ?>                
                                <option value="<?php echo $row3['Area']; ?>"> <?php echo $row3['Area']; ?> </option>
                                <?php 
                                  } ?>
                                </select>  
                                <br>   
                            </div>   
                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <label class="col-md-12">Equipo:</label> 
                                <select class="col-md-12" name="equipo" <?php echo $Activo;?>>
                                <?php                    
                                $sql2 = "SP_TICKETS_FGSS_IMPRESORAS";
                                $proc_result2 = sqlsrv_prepare($conn, $sql2);
                                sqlsrv_execute($proc_result2);
                                  while($row3=sqlsrv_fetch_array( $proc_result2, SQLSRV_FETCH_ASSOC)) 
                                  {
                                    $mq =  $row3['Impresora'].' - '.$row3['IP']
                                ?>                
                                <option value="<?php echo $mq; ?>"> <?php echo $mq; ?> </option>
                                <?php 
                                  } ?>
                                </select>     
                                <br>
                            </div>
                            <div class="col-md-6">                              
                                <label class="col-md-12">Motivo:</label>  
                                <select class="col-md-12" name="motivo" <?php echo $Activo;?>>
                                <?php                    
                                $sql2 = "SP_SSIS_MOTIVOS '1'".$motivo;
                                $proc_result2 = sqlsrv_prepare($conn, $sql2);
                                sqlsrv_execute($proc_result2);
                                  while($row3=sqlsrv_fetch_array( $proc_result2, SQLSRV_FETCH_ASSOC)) 
                                  {
                                ?>                
                                <option value="<?php echo $row3['Motivo']; ?>"> <?php echo $row3['Motivo']; ?> </option>
                                <?php 
                                  } ?>
                                </select>    
                                <br> 
                            </div>   
                        </div>  
                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="col-md-12">Descripcion del problema:</label>                           
                                <div class="col-md-12">
                                    <input type="text" class="form-control" placeholder="" value=""  pattern=[a-zA-Z0-9.,@/*\]{0,300} name="detail" <?php echo $Activo;?>>
                                </div>
                            </div>   
                        </div>
                        <!-- Button trigger modal -->
                        <div class="col-md-12"> </br>
                            <button type="submit" class="btn btn-info waves-effect waves-light m-t-10">Registrar Solicitud de Soporte</button>
                        </div>
                </form>
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
        <script src="plugins/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
        

<script>
  $( function() { 
    $( "#planta" ).selectmenu({
      change: function( event, data ) {
        window.location.href="pag.php?curso=<?php echo $curso;?>&rev=<?php echo $rev;?>&nop="+nope;
      }
     });
 
    $( "#dptos" ).selectmenu({
       change: function( event, data ) {
         
       }
     });
  } );
  </script>








        </body>

</html>