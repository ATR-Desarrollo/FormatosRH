<?php include "initialize.php";?>
<?php include "header.php";?>
<?php include "navbar.php";?>

<script>
function vercertificado (URL){ 
   window.open(URL,"Certificado","height=800,width=1150,Left=50,Top=50,Scrollbars=NO,Directories=NO,resizable=NO");
} 
</script>




<div id='contenido'>
    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <h4 class="page-title">Detalle de cursos</h4>
                </div>

                <!-- /.col-lg-12 -->
            </div>
            <!-- .row -->

            <div class="white-box">
                <div class="form-group">
                    <div class="label-inverse"> <label class="text-white"> Cursos Disponibles:</label> </div>
                    <?php
                      $sql = "SP_CONSULTA_CURSOS_disponibles  '".$_SESSION['NoReloj']."'";
                      $proc_result2 = sqlsrv_prepare($conn, $sql);
                      sqlsrv_execute($proc_result2);                      
                      while($row2=sqlsrv_fetch_array( $proc_result2, SQLSRV_FETCH_ASSOC)) {                        
                        $IdCurso = ($row2['id']);  
                        $Tema = ($row2['Primer']);  
                        $Nombre = ($row2['Nombre']);  
                    ?>
                    <div class="col-md-10"></br>
                        <label> <?php echo $Nombre; ?> </label>
                    </div>
                    <div class="col-md-2">
                        <a href="usr_cursos_presentar.php?id=<?php echo $IdCurso; ?>&t=<?php echo $Tema; ?>">
                            <button type="button" class="btn btn-info waves-effect waves-light m-t-10">Presentar curso</button></a>
                    </div> </br>
                    <?php } ?>
                    </div>
                     
            </div>

            <div class="white-box">
                <div class="form-group">
                    <div class="label-inverse col-md-12"> <label class="text-white"> Historial de cursos del participante</label> <br> </div>
                      </br> <table class="table" style="font-size:12px;color:white;">
                        <thead>
                            <tr style="background-color:blue;font-size:14px;">
                                <th style="color:white;">Curso</th>
                                <th style="color:white;">Fecha Evaluacion</th>
                                <th style="color:white;">Resultado</th>
                                <th style="color:white;">Aprobado</th>
                                <th style="color:white;">Certificado </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                      $sql2 = "[SP_CONSULTA_CURSOS_ASOCIADO]  '".$_SESSION['NoReloj']."'";
                      $proc_result3 = sqlsrv_prepare($conn, $sql2);
                      sqlsrv_execute($proc_result3);                      
                      while($row3=sqlsrv_fetch_array( $proc_result3, SQLSRV_FETCH_ASSOC)) {                        
                        $IdCurso = ($row3['IdCurso']);  
                        $Nombre = ($row3['Nombre']);  
                        $Fecha = ($row3['Fecha']);  
                        $Resultado = ($row3['Resultado']);  
                        $Aprobado = ($row3['Aprobado']);   ?>
                            <tr>
                                <td style="display: <?php echo $muestra;?>;"><?php echo utf8_encode($Nombre); ?></td>
                                <td style="display: <?php echo $muestra;?>;"><?php echo utf8_decode($Fecha); ?></td>
                                <td style="display: <?php echo $muestra;?>;"><?php echo utf8_decode($Resultado); ?></td>
                                <td style="display: <?php echo $muestra;?>;"><?php echo utf8_decode($Aprobado); ?></td>
                                <td style="display: <?php echo $muestra;?>;">
                                <?php if ($Aprobado=="Si") { ?><a class="btn btn-success" onClick="vercertificado('certificado.php?id=<?php echo $IdCurso;?>');">Obtener</a><?php } else {?>
                                  <a class="btn btn-primary" href="recursar.php?id=<?php echo $IdCurso;?>">Recursar</a> <?php } ?>           
                                </td>
                            </tr>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

</div>
<?php include "footer.php";?>


</body>

</html>