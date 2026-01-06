<?php
session_start();
include("conexion.php");

if(isset( $_POST["nume"])){
  $chkCreada = 0;
  $nume=$_POST["nume"];
  $nomb=$_POST["nomb"];
  $plnt=$_POST["plnt"];
  $depa=$_POST["dept"];
  $mail=$_POST['mail'];

  $tipo=$_POST["tipo"];
  $conf=$_POST["conf"];
  $tipa=$_POST["tipa"];
  $nomc=$_POST["carp"];
  $ruta=$_POST["ruta"];
  $tama=$_POST["tama"];
  $moti=$_POST["moti"];

  for ($i = 1; $i <= 40; $i++) {
    if(isset( $_POST["usr".$i])){
    $usr[$i] = $_POST["usr".$i].$_POST["usrDom".$i];
    $opt[$i] = $_POST["usr".$i."_op"];
    }
  }
  
  $sql = " [SP_SCCIS_INFO_CARPETA] '".$nomc."', '".$plnt."', '".$depa."'";
  $proc_carpeta = sqlsrv_prepare($conn, $sql);
  sqlsrv_execute($proc_carpeta);
  $infCarp=sqlsrv_fetch_array($proc_carpeta, SQLSRV_FETCH_ASSOC);

  if($infCarp['Responsable'] != "" && $tipo=="Nuevo") {
    echo'<script type="text/javascript">
    alert("Solicitud no generada: Carpeta ya existente en esa ruta.");
    window.location.href="solicitud_cc.php";</script>';
    $chkCreada = 1;
  }

if ($chkCreada == 0) {
  $sql = " [SP_SCCIS_SOLICITUD] '".$nume."','".$nomb."','".$mail."','".$plnt."','".$depa."','','".$tipo."','".$conf."','".$tipa."','".$nomc."','".$ruta."','".$tama."','".$moti."'";  
  $Folio = Consulta_dato($sql,$conn)['Folio'] ;
  $sql = "SELECT CorreoResponsable FROM [WDB_APPS].[dbo].[SCCIS_mstCarpetas] WHERE Planta = '$plnt' AND Departamento = '$depa' AND NombreCarpeta = '$nomc'";
  $Correo = Consulta_dato($sql,$conn)['CorreoResponsable'] ;

  $auth = "resp";
  if ($tipo == "Nuevo") {
    $auth = "encsis";
    $sql = "SELECT Correo FROM [WDB_APPS].[dbo].[SCCIS_mstAutorizaciones] WHERE Nivel = 2";
    $proc_result = sqlsrv_prepare($conn, $sql);
    sqlsrv_execute($proc_result);
    $row3=sqlsrv_fetch_array($proc_result, SQLSRV_FETCH_ASSOC);

    $Correo = $row3['Correo'];
    while ($row3=sqlsrv_fetch_array($proc_result, SQLSRV_FETCH_ASSOC)) {
        $Correo = $Correo.", ".$row3['Correo'];
    }
  }
  // $to = 'Roberto.silos@sewsus.com.mx,sergio.barron@sewsus.com.mx';  
  $to = $Correo;
  $to2 = "jesus.teran@sewsus.com.mx";
  $from = 'Content-Type: text/html; charset=UTF-8' . "\r\n". 'From: <notificaciones.sistemas@sewsus.com.mx>' . "\r\n". 'Bcc: jesus.teran@sewsus.com.mx' . "\r\n";
  $subject = "Solicitud de Carpeta Compartidas - Sistemas ATR.";
  $message = 
  'El Usuario: '.$nomb.' ('.$nume.') ha generado una solicitud.
  Departamento: '.$depa.'
  Correo: '.$mail.'
  Folio: '.$Folio.'

  Enlace para ver la solicitud:
  http://smatrsaulocal:8080/Apps/wisa/estatus_solicitud_cc.php?Nofolio='.$Folio.'&authUser='.$auth;

  $message = nl2br($message);

  for ($i = 1; $i <= 40; $i++) {
    if(isset( $usr[$i])){
    $sql = "SP_SCCIS_SOLICITUD_Users '".$Folio."','".$usr[$i]."','".$opt[$i]."'";
    Consulta_dato($sql,$conn)['Permiso'];
    }
  }

  mail($to, $subject, $message, $from);

  // mail($to2, $subject, $message, $from);

  echo'<script type="text/javascript">
  alert("Se ha generado su solicitud con Folio: '.$Folio.'");        
  window.location.href="solicitud_cc.php";        </script>';
}
else
{
  echo'<script type="text/javascript">        
  alert("No ha sido posible generar su solicitud. Intentelo nuevamente.");        
  window.location.href="solicitud_cc.php";        </script>';
}
}
?>