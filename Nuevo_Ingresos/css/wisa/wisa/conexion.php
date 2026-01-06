<?php
/******************************************************  
     GENERA LA CADENA DE CONEXION Y REALIZA LA CONEXION
******************************************************/
$serverName = "172.30.75.11"; 
$connectionInfo = array("Database"=>"WDB_APPS","UID"=>"localapps","PWD"=>"L0c@lapp", "CharacterSet" => "UTF-8");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$serverCIDB = '172.30.75.22';
$connectionCIInfo = array("Database"=>"CIDB","UID"=>"localapps","PWD"=>"L0c@lapp", "CharacterSet" => "UTF-8");
$connCI = sqlsrv_connect( $serverCIDB, $connectionCIInfo);

/******************************************************  
     SI FALLA MUESTRA UN MENSAJE, 
     NO ES NESESARIO SI LA CONEXION SE REALIZO CORRECTAMENETE
******************************************************/
if( $conn ) {
     //echo "Conexion establecida.<br />";
}else{
     echo "<center>
     <font color='black' size='50px'>
     <b>NO SE PUDO ESTABLECER CONEXION</b><br>
     </font>
     <font color='red' size='50px'>
     <b>AVISE A SISTEMAS!</b>
     </font>
     </center>
     <br />";
     echo "<br>";
     echo "
     <center>
     <img src='./img/advertencia.png'>
     </center>";
     echo "<font size='5px'>
     <b>Descripcion del error: </b>
     <i>Cambio de direccion IP</i>
     </font> </br>";
     die( print_r( sqlsrv_errors(), true));
}

if( $connCI ) {
     //echo "Conexion establecida.<br />";
}else{
     echo "<center>
     <font color='black' size='50px'>
     <b>NO SE PUDO ESTABLECER CONEXION</b><br>
     </font>
     <font color='red' size='50px'>
     <b>AVISE A SISTEMAS!</b>
     </font>
     </center>
     <br />";
     echo "<br>";
     echo "
     <center>
     <img src='./img/advertencia.png'>
     </center>";
     echo "<font size='5px'>
     <b>Descripcion del error: </b>
     <i>Cambio de direccion IP</i>
     </font> </br>";
     die( print_r( sqlsrv_errors(), true));
}

/******************************************************  
     FUNCION DE CONSULTA
******************************************************/
function Consulta_dato($query,$cconex)
{
     // $params=array();
     // $options=array("Scrollable" => SQLSRV_CURSOR_KEYSET );  
     // $stmt = sqlsrv_query( $cconex, $query , $params, $options );
     // $row_count = sqlsrv_num_rows( $stmt );
     // $row=sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);

     $stmt = sqlsrv_prepare($cconex, $query);
     sqlsrv_execute($stmt);
     $row=sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
     
     return $row;
}

/******************************************************  
     FUNCION PARA EJECUTAR UNA QUERY
******************************************************/
function Executa_query($query,$cconex)
{
     $stmt = sqlsrv_prepare( $cconex, $query,array());
     sqlsrv_execute($stmt);
     $valor_devuelto = "Ok";
     return $valor_devuelto;
}

/******************************************************  
     FUNCION PARA ENVIAR CORREO DE RECHAZO
******************************************************/
function sendMailRechazo($noFolio,$correo)
{
     $to = $correo;
     $from = 'Content-Type: text/html; charset=UTF-8' . "\r\n". 'From: <info.sistemas@sewsus.com.mx>' . "\r\n". 'Bcc: jesus.teran@sewsus.com.mx' . "\r\n";
     $subject = "Solicitud de Carpeta Compartidas - Sistemas ATR.";
     $message = 
'Su solicitud ha sido rechazada, revise el enlace para ver la razon:
http://smatrsaulocal:8080/Apps/wisa/estatus_solicitud_cc.php?Nofolio='.$noFolio;

$message = nl2br($message);

mail($to, $subject, $message, $from);
}

/******************************************************  
     FUNCION PARA ENVIAR CORREO DE NOTIFICACION
******************************************************/
function sendMail($cconex,$noFolio)
{
     $sql = "SELECT * FROM WDB_APPS.dbo.SCCIS_Solicitud WHERE Folio = '$noFolio'";
     $proc_result = sqlsrv_prepare($cconex, $sql);
     sqlsrv_execute($proc_result);
     $row3=sqlsrv_fetch_array($proc_result, SQLSRV_FETCH_ASSOC);

     $nume=$row3["NoReloj"]; 
     $nomb=$row3["Nombre"]; 
     $depa=$row3["Departamento"]; 
     $enca=$row3["Encargado"]; 
     $mail=$row3['CorreoSolicitud'];

     $sql = "SELECT Correo FROM [WDB_APPS].[dbo].[SCCIS_mstAutorizaciones] WHERE Nivel = 2";
     $proc_result = sqlsrv_prepare($cconex, $sql);
     sqlsrv_execute($proc_result);
     $row3=sqlsrv_fetch_array($proc_result, SQLSRV_FETCH_ASSOC);

     $to = $row3['Correo'];
     while ($row3=sqlsrv_fetch_array($proc_result, SQLSRV_FETCH_ASSOC)) {
          $to = $to.", ".$row3['Correo'];
     }
     
     $from = 'Content-Type: text/html; charset=UTF-8' . "\r\n". 'From: <info.sistemas@sewsus.com.mx>' . "\r\n". 'Bcc: jesus.teran@sewsus.com.mx' . "\r\n";
     $subject = "Solicitud de Carpeta Compartidas - Sistemas ATR.";
     $message = 
'El Usuario: '.$nomb.' ('.trim($nume).') ha generado una solicitud.
Departamento: '.$depa.'
Correo: '.$mail.'
Folio: '.$noFolio.'

Enlace para ver la solicitud:
http://smatrsaulocal:8080/Apps/wisa/estatus_solicitud_cc.php?Nofolio='.$noFolio.'&authUser=encsis';

$message = nl2br($message);

     mail($to, $subject, $message, $from);

     // $to2 = 'jesus.teran@sewsus.com.mx';
     // $subject2 = "Solicitud de Carpeta Compartidas - Encargado Sistemas.";

     // mail($to2, $subject2, $message, $from);
}

function sendMailRespCC($cconex,$noFolio)
{
     $sql = "SELECT * FROM WDB_APPS.dbo.SCCIS_Solicitud WHERE Folio = '$noFolio'";
     $proc_result = sqlsrv_prepare($cconex, $sql);
     sqlsrv_execute($proc_result);
     $row3=sqlsrv_fetch_array($proc_result, SQLSRV_FETCH_ASSOC);

     $nume=$row3["NoReloj"]; 
     $nomb=$row3["Nombre"]; 
     $depa=$row3["Departamento"]; 
     $enca=$row3["Encargado"]; 
     $mail=$row3['CorreoSolicitud'];

     $sql = "SELECT B.CorreoResponsable AS Correo FROM SCCIS_mstCarpetas B LEFT JOIN SCCIS_Solicitud A ON A.Carpeta_Nombre = B.NombreCarpeta AND A.Carpeta_Ruta = B.Ruta WHERE Folio = '$noFolio'";
     $proc_result = sqlsrv_prepare($cconex, $sql);
     sqlsrv_execute($proc_result);
     $row3=sqlsrv_fetch_array($proc_result, SQLSRV_FETCH_ASSOC);

     $to = $row3['Correo'];
     while ($row3=sqlsrv_fetch_array($proc_result, SQLSRV_FETCH_ASSOC)) {
          $to = $to.", ".$row3['Correo'];
     }
     
     $from = 'Content-Type: text/html; charset=UTF-8' . "\r\n". 'From: <notificaciones.sistemas@sewsus.com.mx>' . "\r\n". 'Bcc: jesus.teran@sewsus.com.mx' . "\r\n";
     $subject = "Solicitud de Carpeta Compartidas - Sistemas ATR.";
     $message = 
'El Usuario: '.$nomb.' ('.trim($nume).') ha generado una solicitud.
Departamento: '.$depa.'
Correo: '.$mail.'
Folio: '.$noFolio.'

Enlace para ver la solicitud:
http://smatrsaulocal:8080/Apps/wisa/estatus_solicitud_cc.php?Nofolio='.$noFolio.'&authUser=resp';

$message = nl2br($message);

     mail($to, $subject, $message, $from);

     // $to2 = 'jesus.teran@sewsus.com.mx';
     // $subject2 = "Solicitud de Carpeta Compartidas - Encargado Sistemas.";

     // mail($to2, $subject2, $message, $from);
}

/******************************************************  
     FUNCION PARA ENVIAR CORREO DE AUTORIZADO
******************************************************/
function sendMailAutorizado($noFolio,$correo,$ruta,$Carpeta)
{
     $to = $correo;
     $from = 'Content-Type: text/html; charset=UTF-8' . "\r\n". 'From: <info.sistemas@sewsus.com.mx>' . "\r\n". 'Bcc: jesus.teran@sewsus.com.mx' . "\r\n";
     $subject = "Solicitud de Carpeta Compartidas - Sistemas ATR.";
     $message = 
'Su solicitud ha sido autorizada completamente, ya puede ingresar a la carpeta abajo encontrara la liga.
Ruta:'.$ruta.'
Carpeta:'.$Carpeta.'
Revise el enlace para ver el folio:
http://smatrsaulocal:8080/Apps/wisa/estatus_solicitud_cc.php?Nofolio='.$noFolio;

$message = nl2br($message);

mail($to, $subject, $message, $from);
}
?>