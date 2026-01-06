
<?php
  session_start();
  include("conexion.php");
  $conn = sqlsrv_connect( $serverName, $connectionInfo);
  $UsrInput= $_SESSION['Usuario'];
?>
<script src="plugins/bower_components/jquery/dist/jquery.min.js"></script>
<div class="white-box">
                <div class="table-responsive">
                  <table class="table"  style="font-size:12px;">
                    <thead>
                      <tr style="background-color:blue;font-size:14px;">
                      <th style="color:white;font-weight:bolder;">No. Curso</th>
                      <th style="color:white;font-weight:bolder;">Curso</th>
                      <th style="color:white;font-weight:bolder;">Creador</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                      $muestra="";
                    ?>
                    <?php
                      $sql2 = "SELECT NoCurso, Nombre, Creador FROM Cursos Order By NoCurso";
                      $proc_result2 = sqlsrv_prepare($conn, $sql2);
                      sqlsrv_execute($proc_result2);
                      while($row3=sqlsrv_fetch_array( $proc_result2, SQLSRV_FETCH_ASSOC)) {
                    ?>
                    <tr>
                    <?php
                      $muestra="visible";
                    ?>
                      <td style="display: <?php echo $muestra;?>;"><?php echo utf8_decode($row3['NoCurso']); ?></td>
                      <td style="display: <?php echo $muestra;?>;"><?php echo utf8_decode($row3['Nombre']); ?></td>
                      <td style="display: <?php echo $muestra;?>;"><?php echo utf8_decode($row3['Creador']); ?></td>
                      </tr>
                      <?php }
                    ?>
                   </table>
                </div>
             </div>
