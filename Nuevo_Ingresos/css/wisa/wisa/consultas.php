<?php include 'initialize.php';?>
<?php include 'header.php';//AGREGA BARRA DE ENCABEZADO?>

<?php 
/*****************************************************************************************
 *  CARGA LIBRERIAS NESESARIAS PARA DATATABLES, ESTILOS, Y BOTONES PARA DESCARGA E IMPRESION 
 *****************************************************************************************/?>

<!--
<link href="datatables/buttons/1.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="plugins/bower_components/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="plugins/bower_components/jquery/dist/jquery.min.js"></script>
<script src="cloudflare/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="plugins/bower_components/datatables/jquery.dataTables.min.js"></script>
<script src="datatables/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="rawgit/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script src="datatables/buttons/1.2.2/js/buttons.html5.min.js"></script>
<script src="datatables/buttons/1.2.2/js/buttons.print.min.js"></script>

-->
<?php include 'navbar.php';//AGREGA MENU?>

<?php    
    if(!empty($_POST['id'])){ $id = $_POST['id']; }
    else{ $id = "";}  
    if(!empty($_POST['rev'])){ $rev = $_POST['rev']; }
    else{ $rev = "";} 
?>

<div id='contenido'>
    <!-- Page Content -->
    <div id='page-wrapper'>
        <div class='container-fluid'>
            <div class='row bg-title'>
                <div class='col-lg-10 col-md-6 col-sm-12 col-xs-12'>
                    <h4 class='page-title'>Consultar estatus de Solicitudes</h4>
                </div>
                <div class='col-lg-9 col-sm-8 col-md-8 col-xs-12'>
                    <ol class='breadcrumb'></ol>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- .row -->


            <?php 
/*****************************************************************************************
 *  SECCION DE CODIGO QUE MUESTRA LOS CURSOS CON DETALLE QUE PODEMOS SELECCIONAR
 *****************************************************************************************/?>
            



<?php 
/*****************************************************************************************
 *  MUESTRA LA TABLA CON LOS RESULTADOS DE LA BUSQUEDA
 *****************************************************************************************/?>
<title> [Consulta de Resultados] </title>
            <div class="col-sm-12">
                <div class="white-box">
                        <table id="tabla" class="display nowrap" >
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Solicitud</th>
                                    <th>Nombre Carpeta</th>
                                    <th>Ruta</th>
                                    <th>Tamaño</th>
                                    <th>Confidencialidad</th>
                                    <th>Motivo</th>
                                    <th>Tipo de Archivos</th>
                                    <th>Estatus</th>
                                    <th>+</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                            $sql2 = "[SP_CONSULTA_CURSOS_AVANCE]  '".$id."'";
                            $proc_result3 = sqlsrv_prepare($conn, $sql2);
                            sqlsrv_execute($proc_result3);                      
                            while($row3=sqlsrv_fetch_array( $proc_result3, SQLSRV_FETCH_ASSOC)) {                        
                                $IdCurso = $row3['IdCurso'];  
                                $NoReloj = $row3['NoReloj'];
                                $Nombre = $row3['Nombre'];  
                                $Dep = $row3['Departamento'];  
                                if(!empty($row3['Fecha']))
                                { $Fecha = $row3['Fecha'] -> format('d-m-Y');} 
                                else {$Fecha="-";}
                                
                                If ($Resultado>="90"){
                                    $Aprobado = "Aprobado";
                                } else If ($Resultado==""){$Aprobado="";}
                                else {$Aprobado="Reprobado";}                           
                                ?>
                                <tr>
                                    <td><?php echo $Pla; ?></td>
                                    <td><?php echo $Dep; ?></td>
                                    <td><?php echo $NoReloj; ?></td>
                                    <td><?php echo $Nombre; ?></td>
                                    <td><?php echo $avance."%"; ?></td>
                                    <td><?php echo $Resultado; ?></td>
                                    <td><?php echo $intentos; ?></td>
                                    <td><?php echo $Fecha; ?></td>
                                    <td><?php echo $Aprobado; ?></td>
                                </tr>
                                <?php } ?>

                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
    


<?php 
/*****************************************************************************************
 *  SCRYPT QUE CARGA EN TABLA LAS CONFIGURACIONES DEL DOM Y MUESTRA BOTONES 
 *****************************************************************************************/?>
 <script>
$('#tabla').DataTable({
    dom: 'B',
    
    buttons: ['excel', 'print']
});
</script>

<?php include 'footer.php';?>
</body>
</html>
