<?php include 'initialize.php';?>
<?php include 'header.php';//AGREGA BARRA DE ENCABEZADO?>
<?php
    $conn = sqlsrv_connect( $serverName, $connectionInfo);

    $sql = " SELECT TOP 50 A.* FROM SCCIS_Solicitud A ORDER BY A.Folio DESC ";
    $proc_result = sqlsrv_prepare($conn, $sql);
    sqlsrv_execute($proc_result);
?>

<?php include 'navbar.php';?>

<div id='contenido'>
    <!-- Page Content -->
    <div id='page-wrapper'>
        <div class='container-fluid'>
            <div class='row bg-title'>
                <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12" style="display:flex; justify-content:space-between;align-items:center;">
                    <h4 class='page-title'>Consulta Solicitudes de Carpetas Compartidas</h4>
                    <a href="solicitud_cc.php" role="button">
                        <button type="button" class="btn btn-info waves-effect waves-light">Crear solicitud</button>
                    </a>
                </div>
                <div class='col-lg-9 col-sm-8 col-md-8 col-xs-12'>
                    <ol class='breadcrumb'></ol>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- .row -->
        <title> [Consulta Solicitudes de CC] </title>
        <?php
        if (isset($_POST['btnBuscar'])) {
            $fechaInicio = $_POST['fecha_inicio'];
            $fechaFin = $_POST['fecha_fin'];
            $sql = " [SP_SCCIS_CONSULTA_SOLICITUD] '$fechaInicio','$fechaFin' ";
            $proc_result = sqlsrv_prepare($conn, $sql);
            sqlsrv_execute($proc_result);
        }
        ?>
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <div class="form-horizontal form-material">
                        <div class="label-inverse">
                            <label class="text-white">Tabla Solicitudes Pendientes</label>
                        </div>
                        <br>
                        <form method="POST">
                            <div style="display:flex; justify-content:space-between;">
                                <div style="display:block;width:49%;">
                                    <p>Fecha Inicial:</p>
                                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio"   Value ="<?php echo $fechaInicio; ?>">                            
                                </div>
                                <div style="display:block;width:49%;">
                                    <p>Fecha Final:</p>
                                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?php echo $fechaFin; ?>">    
                                </div>
                            </div>
                            <br/>
                            <div>
                                <input class="form-control" type="submit" name="btnBuscar" value="Buscar">
                            </div>
                        </form>
                    </div>

                    <div class="form-horizontal form-material">
                    <br/>
                        <table id="tabla" class="table" style="font-size:12px;color:white;">
                            <thead>
                                <tr class="label-inverse" style="font-size:14px;">
                                    <th style="color:white;text-align:center;" scope="col;">Folio</th>
                                    <th style="color:white;text-align:center;" scope="col;">No Reloj</th>
                                    <th style="color:white;text-align:center;" scope="col;">Nombre</th>
                                    <th style="color:white;text-align:center;" scope="col;">Departamento</th>
                                    <th style="color:white;text-align:center;" scope="col;">Tipo de Solicitud</th>
                                    <!-- <th style="color:white;text-align:center;" scope="col;">Confidencialidad</th> -->
                                    <th style="color:white;" scope="col;">Nombre de Carpeta</th>
                                    <th style="color:white;" scope="col;">Motivo</th>
                                    <th style="color:white;" scope="col;">Fecha Captura</th>
                                    <th style="color:white;" scope="col;">Estatus</th>
                                    <th style="color:white;" scope="col;">Ver Autorizacion</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php while($row3=sqlsrv_fetch_array($proc_result, SQLSRV_FETCH_ASSOC)) { ?>
                                <tr <?php if ($row3['Estatus'] == -1) {echo 'style="background-color:#fa7890;"';}?>>
                                    <td style="color:black;text-align:center;"> <?php echo ($row3['Folio']); ?></td>
                                    <td style="color:black;text-align:center;"> <?php echo ($row3['NoReloj']); ?></td>
                                    <td style="color:black;text-align:center;"> <?php echo ($row3['Nombre']); ?></td>
                                    <td style="color:black;text-align:center;"> <?php echo ($row3['Departamento']); ?></td>
                                    <td style="color:black;text-align:center;"> <?php echo ($row3['Tipo_Solicitud']); ?></td>
                                    <!-- <td style="color:black;text-align:center;"><?php //echo ($row3['Confidencial']); ?></td> -->
                                    <td style="color:black; "><?php echo ($row3['Carpeta_Nombre']); ?></td>
                                    <td style="color:black;"><?php echo ($row3['Motivo']); ?></td>
                                    <td style="color:black;text-align:center;"><?php echo $row3['Fecha_captura'] -> format('d/m/Y');; ?></td>
                                    <td style="color:black;">
                                        <?php if($row3['Estatus'] == 0) {
                                            echo 'Sin autorizar';
                                        } else if($row3['Estatus'] == 1) {
                                            echo 'Falta autorizacion de Sistemas';
                                        } else if($row3['Estatus'] == 2) {
                                            echo 'Autorizada'; 
                                        } else if($row3['Estatus'] == -1) {
                                            echo 'Rechazada';
                                        }
                                        ?>
                                    </td>
                                    <td style="text-align:center;">
                                        <button onclick="verSolicitud('<?php echo  $row3['Folio']; ?>')" class="btn btn-secondary">Ver</button>
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
</div>

<?php include 'footer.php';?>

<script>
    $(document).ready( function () {
        $('#tabla').DataTable({
            // Make the first column sort ascending by default
            "order": [[ 0, "desc" ]]
        });
    });

    function verSolicitud(folio) {
        window.location.href = "estatus_solicitud_cc.php?Nofolio=" + folio;
    }
</script>

</body>
</html>
