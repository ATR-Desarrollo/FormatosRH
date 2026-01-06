<?php
session_start();
include("conexion.php");

if(isset( $_POST["No_Reloj"])){
  
  $nr = $_POST["No_Reloj"]; 
  $nm = $_POST["name"];
  $do = $_POST["dpto"];
  $ip = $_POST["ip"];
  $pl = $_POST["planta"];
  $mt = $_POST["motivo"];
  $Up = $_POST["Usuario"];
  $pw = ""; //$_POST["Passwor"];
  $ml = $_POST["mail"];
  $rr = $_POST["ReqResp"];
  $rt = $_POST["RutResp"];
  

  $sql = "SP_SFIS_SOLICITUD '".$nr."','".$nm."','".$ip."','".$Up."','".$pw."','".$ml."','".$rr."','".$rt."','".$pl."','".$do."','".$mt."'";  
  $ticket = Consulta_dato($sql,$conn)['Solicitud'] ;
  echo $ticket;

   $to = 'roberto.silos@sewsus.com.mx,sergio.barron@sewsus.com.mx,Jesus.Piedra@sewsus.com.mx';
  /*$to = 'SauloATRIS@sewsus.com.mx'; /*  */

  $from = 'info.sistemas@sewsus.com.mx';
  $subject = "Solicitud para Formateo de equipo - Sistemas ATR.";
  $message = 
 'El Usuario: '.$nm.' ('.$nr.') ha solicitado Formateo de un Equipo de computo.    
  User IP: '.$ip.'
  Folio: '.$ticket.'
  
  DATOS DEL SOPORTE
  Planta: '.$pl.'
  Departamento: '.$do.'
  Usuario: '.$Up.'   
  Requiere Respaldo: '.$rr.' 
  Ruta del Respaldo: '.$rt.' 
  Motivo: '.$mt.' 
  '; 

  echo $message;
  mail($to, $subject, $message, '',$from);

  echo'<script type="text/javascript">        
  alert("Ticket para Formateo Generedo: '.$ticket.'");        
  window.location.href="Formateo.php";        </script>';
}
else
{
  echo'<script type="text/javascript">        
  alert("Ticket de formateo no generado. Intentelo nuevamente mas tarde.");        
  window.location.href="Formateo.php";        </script>';
}
?>