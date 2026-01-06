<?php include 'initialize.php';?>

<?php include 'header.php';//AGREGA BARRA DE ENCABEZADO?>

<?php include 'navbar.php';?>

<?php
    if(!empty($_GET)) {
        $noFolio = $_GET["Nofolio"];
        if (isset( $_GET["authUser"])) {
            $authUser = $_GET["authUser"];
        }
        $sql = "SELECT * FROM WDB_APPS.dbo.SCCIS_Solicitud WHERE Folio = '$noFolio'";
        $proc_result = sqlsrv_prepare($conn, $sql);
        sqlsrv_execute($proc_result);
        $row3=sqlsrv_fetch_array($proc_result, SQLSRV_FETCH_ASSOC);
        $NomC = $row3['Carpeta_Nombre'];
        $Planta = $row3['Planta'];
        $Departamento = $row3['Departamento'];
        $Ruta = $row3['Carpeta_Ruta'];
    } else { // SI NO EXISTE EL FOLIO, MANDA MENSAJE DE ERROR             
        echo'<script type="text/javascript">
        alert("Error al realizar consulta");
        window.location.href="solicitud_cc.php";
        </script>';
    }
?>

<?php
    $sqlC = " [SP_SCCIS_INFO_CARPETA] '".$NomC."', '".$Planta."', '".$Departamento."'";
    $proc_carpeta = sqlsrv_prepare($conn, $sqlC);
    sqlsrv_execute($proc_carpeta);
    $infCarp=sqlsrv_fetch_array($proc_carpeta, SQLSRV_FETCH_ASSOC);

    $sql = "SELECT Estatus, Comentarios FROM WDB_APPS.dbo.SCCIS_Autorizaciones WHERE Folio = '$noFolio'";
    $porc_estat = sqlsrv_prepare($conn, $sql);
    sqlsrv_execute($porc_estat);
    $rowEst=sqlsrv_fetch_array($porc_estat, SQLSRV_FETCH_ASSOC);
?>

<?php
    if (!is_null($infCarp)){
        if ($infCarp['Ruta'] != '') {
            $Ruta = $infCarp['Ruta'];
        }
    }

    if (isset($_POST['btnRechazar'])) {
        $Comentarios = $_POST['coment'];
        $sql = " UPDATE WDB_APPS.dbo.SCCIS_Autorizaciones SET Estatus = -1, Comentarios = '$Comentarios' WHERE Folio = '$noFolio' ";
        $proc_result = sqlsrv_prepare($conn, $sql);
        sqlsrv_execute($proc_result);
        $sql = " UPDATE WDB_APPS.dbo.SCCIS_Solicitud SET Estatus = -1 WHERE Folio = '$noFolio' ";
        Executa_query($sql,$conn);
        sendMailRechazo($noFolio,$row3['CorreoSolicitud']);
    }
    if (isset($_POST['btnResp'])) {
        $sql = " UPDATE WDB_APPS.dbo.SCCIS_Autorizaciones SET Estatus = 1 WHERE Folio = '$noFolio' ";
        $proc_result = sqlsrv_prepare($conn, $sql);
        sqlsrv_execute($proc_result);
        $sql = " UPDATE WDB_APPS.dbo.SCCIS_Solicitud SET Estatus = 1 WHERE Folio = '$noFolio' ";
        Executa_query($sql,$conn);
        sendMail($conn,$noFolio);
        $sql = "INSERT INTO WDB_APPS.dbo.SCCIS_AutorizacionesDtl VALUES ('$noFolio',GETDATE(),'".$infCarp['Responsable']."')";
        Executa_query($sql,$conn);
    }
    if (isset($_POST['btnSist'])) {
        $chkmail = $_POST['chkmail'];
        $chkresp = $_POST['chkresp'];
        $chknorj = $_POST['chknorj'];
        $Ruta = $_POST['rutaF'];
        $NomC = $_POST['nomcF'];

        $sql = " [SP_SCCIS_INFO_CARPETA] '".$NomC."', '".$Planta."', '".$Departamento."'";
        $proc_carpeta = sqlsrv_prepare($conn, $sql);
        sqlsrv_execute($proc_carpeta);
        $infCarp=sqlsrv_fetch_array($proc_carpeta, SQLSRV_FETCH_ASSOC);

        if ($infCarp['Responsable'] != "" && $row3['Tipo_Solicitud']=="Nuevo") {
            echo'<script type="text/javascript">
            alert("Autorizacion fallida: Carpeta ya existente en esa ruta.");
            window.location.href="estatus_solicitud_cc.php?Nofolio='.$noFolio.'&authUser=encsis";
            </script>';
            return;
        }

        if ($row3['Tipo_Solicitud'] == 'Nuevo') {
            if ($_POST['chkmail'] == '') {
                echo'<script type="text/javascript">
                alert("Ingrese al responsable de la carpeta.");
                window.location.href="estatus_solicitud_cc.php?Nofolio='.$noFolio.'&authUser=encsis";
                </script>';
                return;
            } else {
                $sql = " [SP_SCCIS_CREA_CARPETA] '".$row3['Planta']."', '".$row3['Departamento']."', '".$NomC."', '".$Ruta."', '".$row3['Confidencial']."', '".$row3['Carpeta_Espacio']."', '".$chknorj."', '".$chkresp."', '".$chkmail."' ";
                Executa_query($sql,$conn);
                $sql = " UPDATE WDB_APPS.dbo.SCCIS_Solicitud SET Carpeta_Nombre = '".$NomC."', Carpeta_Ruta = '".$Ruta."' WHERE Folio = '".$noFolio."' ";
                Executa_query($sql,$conn);

                sendMailAutorizado($noFolio,$row3['CorreoSolicitud'],$Ruta, $NomC);
            }
        }
        $sql = " UPDATE WDB_APPS.dbo.SCCIS_Autorizaciones SET Estatus = 2 WHERE Folio = '$noFolio' ";
        Executa_query($sql,$conn);
        $sql = " UPDATE WDB_APPS.dbo.SCCIS_Solicitud SET Estatus = 2 WHERE Folio = '$noFolio' ";
        Executa_query($sql,$conn);
        $sql = " [SP_SCCIS_ACTUALIZA_ESTATUS_USUARIOS] '$noFolio','$Ruta','$NomC' ";
        Executa_query($sql,$conn);
        $sql = "INSERT INTO WDB_APPS.dbo.SCCIS_AutorizacionesDtl VALUES ('$noFolio',GETDATE(),'Encargado Sistemas')";
        Executa_query($sql,$conn);

        sendMailAutorizado($noFolio,$row3['CorreoSolicitud'],$Ruta, $NomC);
    }

    $sqlC = " [SP_SCCIS_INFO_CARPETA] '".$NomC."', '".$Planta."', '".$Departamento."'";
    $proc_carpeta = sqlsrv_prepare($conn, $sqlC);
    sqlsrv_execute($proc_carpeta);
    $infCarp=sqlsrv_fetch_array($proc_carpeta, SQLSRV_FETCH_ASSOC);
    sqlsrv_execute($porc_estat);
    $rowEst=sqlsrv_fetch_array($porc_estat, SQLSRV_FETCH_ASSOC);
?>

<div id='contenido'>
    <div id='page-wrapper'>
        <div class='container-fluid'>
            <div class='row bg-title'>
                <div class='col-lg-12 col-md-6 col-sm-12 col-xs-12' style="display:flex; justify-content:space-between;align-items:center;">
                    <h4 class='page-title'>Estatus Solicitud de Carpeta Compartida</h4>
                    <a href="consulta_solicitud_cc.php" role="button">
                        <button type="button" class="btn btn-info waves-effect waves-light">Ver solicitudes</button>
                    </a>
                </div>
                <div class='col-lg-9 col-sm-8 col-md-8 col-xs-12'>
                    <ol class='breadcrumb'></ol>
                </div>
            </div>
            

            <title> [Estatus de Solicitud de CC] </title>
            <div class="row animated fadeInUp">
                <div class="col-sm-12">
                    <div class="white-box">
                        <div class="form-horizontal form-material">
                            <div class="label-inverse">
                                <label class="text-white">Datos del solicitante</label>
                            </div>
                            <br>
                            <div class="form-group">
                                <label class="col-md-12">No. Reloj: </label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" placeholder=""
                                        value="<?php echo $row3['NoReloj'] ; ?>" readonly name="NoReloj">
                            </div>
                        </div>

                        <div class="form-horizontal form-material">
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label class="col-md-2">Nombre:</label>
                                    <input type="text" class="form-control" placeholder=""
                                        value="<?php echo ($row3['Nombre']) ; ?>" readonly
                                        name="nomb">
                                </div>
                                <div class="col-md-4">
                                    <label class="col-md-4">Planta:</label>
                                    <input type="text" class="form-control" placeholder=""
                                        value="<?php echo $row3['Planta'] ; ?>" readonly
                                        name="depa">
                                </div>
                                <div class="col-md-4">
                                    <label class="col-md-2">Departamento:</label>
                                    <input type="text" class="form-control" placeholder=""
                                        value="<?php echo ($row3['Departamento']) ; ?>" readonly
                                        name="enca">
                                    <input type="hidden" class="form-control" placeholder=""
                                        value="<?php if (isset( $_POST["NoReloj"])) { echo $NoCtr;} ?>" name="nume"
                                        style="visible:false;">
                                </div>
                            </div>

                            <div class="label-inverse">
                                <label class="text-white">Requerimientos</label>
                            </div>
                            <br>
                            <div class="form-group">
                                <label class="col-md-2">Tipo de solicitud:</label>
                                <select class="col-md-4" name="tipo" disabled>
                                    <?php
                                    switch ($row3['Tipo_Solicitud']) {
                                        case 'Nuevo':
                                            $tipo_solicitud = "Creacion de carpeta";
                                            break;
                                        case 'Modifica':
                                            $tipo_solicitud = "Modificacion de permisos";
                                            break;
                                        case 'Elimina':
                                            $tipo_solicitud = "Eliminacion de carpeta";
                                            break;
                                    }
                                    ?>
                                    <option value="solicitud"><?php echo $tipo_solicitud ?></option>
                                </select>
                                <label class="col-md-2">Nivel de confidencialidad:</label>
                                <select class="col-md-4" name="conf" disabled>
                                <?php
                                    switch ($row3['Confidencial']) {
                                        case 'General':
                                            $nivel_confi = "Acceso General";
                                            break;
                                        case 'Confidencial':
                                            $nivel_confi = "Confidencial";
                                            break;
                                        case 'Sensible':
                                            $nivel_confi = "Datos sensibles de personal";
                                            break;
                                        default:
                                            $nivel_confi = "";
                                            break;
                                    }
                                    ?>
                                    <option value="confidencialidad"><?php echo $nivel_confi ?></option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2">Nomb Carpeta:</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" placeholder=""
                                        value="<?php echo $NomC ; ?>" name="nomc"
                                        <?php
                                            if (isset($_GET["authUser"])) {
                                                if ($authUser!='encsis' || $tipo_solicitud == "Modificacion de permisos") {
                                                    echo 'readonly';
                                                }
                                            } else {echo 'readonly';}
                                        ?>
                                        >
                                </div>
                                <label class="col-md-2">Tamaño Estimado (Gb):</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" placeholder=""
                                        value="<?php echo $row3['Carpeta_Espacio'] ; ?>" readonly
                                        name="tama">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2">Ruta de la carpeta:</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" placeholder=""
                                        value="<?php echo $Ruta; ?>" name="ruta"
                                        <?php
                                            if (isset($_GET["authUser"])) {
                                                if ($authUser!='encsis' || $tipo_solicitud == "Modificacion de permisos") {
                                                    echo 'readonly';
                                                }
                                            } else {echo 'readonly';}
                                        ?>
                                        >
                                </div>
                                <label class="col-md-2">Motivo:</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" placeholder=""
                                        value="<?php echo $row3['Motivo'] ; ?>" readonly
                                        name="moti">
                                </div>
                            </div>
                            <script>
                            $(document).ready(function() {
                                $("form[id$='Autorizar']").submit(function(e){
                                    $("#nomcF").val($("input[name$='nomc']").val());
                                    $("#rutaF").val($("input[name$='ruta']").val());
                                });
                                $("#enca").on('change', function (e) {
                                    $("#mail").val(this.value);
                                    $("#chkmail").val(this.value);
                                    var i = $("#enca option:selected").text().split(" | "), n = i[0]; k = i[1];
                                    $("#chknorj").val(n);
                                    $("#chkresp").val(k);
                                });
                            });
                            </script>
                            <div class="form-group">
                                <label class="col-md-2">Responsable de carpeta:</label>
                                <select class="col-md-4" name="enca" id="enca"
                                <?php
                                if (isset($_GET["authUser"])) {
                                    if ($tipo_solicitud == "Creacion de carpeta") {
                                        if($rowEst['Estatus']==2) {
                                            echo 'disabled';
                                        }
                                    } else if ($authUser!='encsis' || $rowEst['Estatus']!=1 || $tipo_solicitud == "Modificacion de permisos") {
                                            echo 'disabled';
                                        } 
                                } else {echo 'disabled';}
                                ?>
                                >
                                    <option value=""><?php if(isset($infCarp)) {echo $infCarp['Responsable'];}?></option>
                                    <?php
                                        $sql = " [SP_SCCIS_LISTA_USUARIOS_ERP] ";
                                        $proc_users = sqlsrv_prepare($conn, $sql);
                                        sqlsrv_execute($proc_users);
                                        while ($users=sqlsrv_fetch_array($proc_users, SQLSRV_FETCH_ASSOC)) {
                                    ?>
                                    <option value="<?php echo $users['correo']; ?>"><?php echo $users['No_reloj']." | ".($users['Nombre']); ?></option>
                                    <?php } ?>
                                </select>
                                <label class="col-md-2">Correo responsable:</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" placeholder=""
                                        value="<?php if(isset($infCarp)) {echo $infCarp['CorreoResponsable'];}?>" id="mail" readonly
                                        name="mail">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2">Tipos de archivos:</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" placeholder=""
                                        value="<?php echo $row3['Tipo_archivos'] ; ?>" readonly
                                        name="tipa">
                                </div>
                            </div>
                            <div class="label-inverse">
                                <label class="text-white">Especificacion de permisos</label>
                            </div>
                            <br/>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col" class="col-md-1">No.</th>
                                        <th scope="col" class="col-md-5">Usuario</th>
                                        <th scope="col" class="col-md-2">Tipo de Permiso</th>
                                    </tr>
                                </thead>
                                <?php
                                    $n = 1;
                                    $sql = "SELECT Usuario, Permiso FROM WDB_APPS.dbo.SCCIS_Solicitud_Usuarios WHERE Folio = '$noFolio'";
                                    $proc_permisos = sqlsrv_prepare($conn, $sql);
                                    sqlsrv_execute($proc_permisos);
                                ?>
                                <?php while($user_perm=sqlsrv_fetch_array($proc_permisos, SQLSRV_FETCH_ASSOC)) { ?>
                                <tbody>
                                    <td style="color:black;"><?php echo $n ?></td>
                                    <td style="color:black;"><?php echo ($user_perm['Usuario']); ?></td>
                                    <td style="color:black;"><?php echo ($user_perm['Permiso']); ?></td>
                                </tbody>
                            <?php $n = $n + 1; } ?>
                            </table>

                            <div class="label-inverse">
                                <label class="text-white">Autorizaciones</label>
                            </div>
                            <br>

                            <div class="form-group">
                                <div class="col-md-6" style="text-align:right">
                                    <label>Responsable de carpeta:</label>
                                    <?php if($rowEst['Estatus']>=1) {
                                        echo '<img src="plugins/images/icn_alert_success.png" alt="home" width="25" height="25" class="dark-logo" />';
                                    } else {
                                        echo '<img src="plugins/images/icn_alert_error.png" alt="home" width="25" height="25" class="dark-logo" />';
                                    }
                                    ?>
                                </div>

                                <div class="col-md-6">
                                    <label>Encargado de Sistemas:</label>
                                    <?php if($rowEst['Estatus']==2) {
                                        echo '<img src="plugins/images/icn_alert_success.png" alt="home" width="25" height="25" class="dark-logo" />';
                                    } else {
                                        echo '<img src="plugins/images/icn_alert_error.png" alt="home" width="25" height="25" class="dark-logo" />';
                                    }
                                    ?>
                                </div>
                            </div>

                            <form action="estatus_solicitud_cc.php?Nofolio=<?php echo $noFolio; ?>" method="POST" id="Autorizar">
                                <div class="col-md-4" style="text-align:center;">
                                    <input class="btn btn-info" type="submit" name="btnResp" value="Autorizar Responsable"
                                    <?php
                                        if (isset($_GET["authUser"])) {
                                            if ($authUser!='resp' || $rowEst['Estatus']!=0) {
                                                echo 'disabled';
                                            }
                                        } else {echo 'disabled';}
                                    ?>
                                    >
                                </div>

                                <div class="col-md-4" style="text-align:center;">
                                    <input class="btn btn-info" type="submit" name="btnSist" value="Autorizar Sistemas"
                                    <?php
                                        if (isset($_GET["authUser"])) {
                                            if ($tipo_solicitud == "Creacion de carpeta") {
                                                if($rowEst['Estatus']==2) {
                                                    echo 'disabled';
                                                }
                                            } else if ($authUser!='encsis' || $rowEst['Estatus']!=1) {
                                                    echo 'disabled';
                                                }
                                        } else { echo 'disabled'; }
                                    ?>
                                    >
                                </div>

                                <div class="col-md-4" style="text-align:center;">
                                    <input class="btn btn-info" type="submit" name="btnRechazar" value="Rechazar Autorizacion"
                                    <?php
                                        if (isset($_GET["authUser"])) {
                                            if ($rowEst['Estatus']==2 || $rowEst['Estatus']==-1) {
                                                echo 'disabled';
                                            }
                                        } else {echo 'disabled';}
                                    ?>
                                    >
                                </div>
                                <br><br>

                                <label class="col-md-2">Comentarios:</label>
                                <input type="text" class="form-control" value="<?php echo $rowEst['Comentarios']; ?>" placeholder="Motivo de rechazo..." name="coment"
                                <?php
                                    if (isset($_GET["authUser"])) {
                                        if ($rowEst['Estatus']==2 || $rowEst['Estatus']==-1) {
                                            echo 'disabled';
                                        }
                                    } else {echo 'disabled';}
                                ?>
                                >
                                <input type="hidden" id="nomcF" name="nomcF" value="">
                                <input type="hidden" id="rutaF" name="rutaF" value="">
                                <input type="hidden" id="chkmail" name="chkmail" value="">
                                <input type="hidden" id="chknorj" name="chknorj" value="">
                                <input type="hidden" id="chkresp" name="chkresp" value="">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<?php include 'footer.php';?>
</body>
</html>
