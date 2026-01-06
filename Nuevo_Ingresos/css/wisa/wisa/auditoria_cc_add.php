<?php
    session_start();
    include("conexion.php");

    if (isset($_POST["planta"])) {
        $noReloj = $_POST['nume'];
        $Usuario = $_POST['nomb'];
        $NomC = $_POST['carp'];
        $Ruta = $_POST['ruta'];
        $Correo = $_POST['mail'];
        $Planta = $_POST['planta'];
        $Depto = $_POST['depto'];
        $Conf = $_POST['conf'];

        $sql = " INSERT INTO [WDB_APPS].[dbo].[SCCIS_AuditoriaCarpetas] VALUES('$Planta','$Depto','$NomC',GETDATE(),'$Usuario','OK-MODIF') ";
        Executa_query($sql,$conn);

        $sql = " [SP_SCCIS_SOLICITUD] '".$noReloj."','".$Usuario."','".$Correo."','".$Planta."','".$Depto."','','Modifica','".$Conf."','','".$NomC."','".$Ruta."','','Auditoria CC'";  
        $Folio = Consulta_dato($sql,$conn)['Folio'];

        $sql = " UPDATE WDB_APPS.dbo.SCCIS_Autorizaciones SET Estatus = 1 WHERE Folio = '$Folio' ";
        Executa_query($sql,$conn);

        $sql = " UPDATE WDB_APPS.dbo.SCCIS_Solicitud SET Estatus = 1 WHERE Folio = '$Folio' ";
        Executa_query($sql,$conn);

        $sql = "SELECT Correo FROM [WDB_APPS].[dbo].[SCCIS_mstAutorizaciones] WHERE Nivel = 2";
        $proc_correo = sqlsrv_prepare($conn, $sql);
        sqlsrv_execute($proc_correo);
        $row3=sqlsrv_fetch_array($proc_correo, SQLSRV_FETCH_ASSOC);

        $Correos = $row3['Correo'];
        while ($row3=sqlsrv_fetch_array($proc_correo, SQLSRV_FETCH_ASSOC)) {
            $Correos = $Correos.", ".$row3['Correo'];
        }

        $to = $Correos;
        // $to = 'jesus.teran@sewsus.com.mx';
        $from = 'Content-Type: text/html; charset=UTF-8' . "\r\n". 'From: <info.sistemas@sewsus.com.mx>' . "\r\n". 'Bcc: jesus.teran@sewsus.com.mx' . "\r\n";
        $subject = "Solicitud de Carpeta Compartidas - Sistemas ATR.";
        $message = 
'El Usuario: '.$Usuario.' ('.trim($noReloj).') ha generado una solicitud.
Departamento: '.$Depto.'
Correo: '.$Correo.'
Folio: '.$Folio.'

Enlace para ver la solicitud:
http://smatrsaulocal:8080/Apps/wisa/estatus_solicitud_cc.php?Nofolio='.$Folio.'&authUser=encsis';

        $message = nl2br($message);

        for ($i = 1; $i <= 40; $i++) {
    if(isset($_POST["usr".$i])){
        if (isset($_POST["usrDom".$i])) {
            $usr[$i] = $_POST["usr".$i].$_POST["usrDom".$i];
        } else {
            $usr[$i] = $_POST["usr".$i];
        }
        $opt[$i] = $_POST["usr".$i."_op"];
        $permiso_actual = isset($_POST["usr".$i."_actual"]) ? trim($_POST["usr".$i."_actual"]) : '';

        // Mapear valor numérico a texto para comparar
        $permiso_nuevo = '';
        switch ($opt[$i]) {
            case '1':
                $permiso_nuevo = 'Solo Lectura';
                break;
            case '2':
                $permiso_nuevo = 'Lectura y Escritura';
                break;
            case '3':
                $permiso_nuevo = 'Eliminar Permisos';
                break;
        }

        // Insertar solo si el permiso nuevo es diferente al actual
        if ($permiso_nuevo !== $permiso_actual) {
            $sql = "SP_SCCIS_SOLICITUD_Users '".$Folio."','".$usr[$i]."','".$opt[$i]."'";
            Consulta_dato($sql,$conn)['Permiso'];
        }
    }
}

        mail($to, $subject, $message, $from);

        echo'<script type="text/javascript">
            alert("Se ha generado su solicitud de modificacion con Folio: '.$Folio.'");
            window.location.href="consulta_solicitud_cc.php";
            </script>';
    }
?>