<?php
session_start();
include("conexion.php");

if(isset( $_POST["No_Reloj"])){
  
  $nr = $_POST["No_Reloj"]; 
  $nm = $_POST["name"];
  $do = $_POST["dpto"];
  $ip = $_POST["ip"];
  $pl = $_POST["planta"];
  $dp = $_POST["dptos"];
  $ar = $_POST["area"];
  $eq = $_POST["equipo"];
  $mt = $_POST["motivo"];
  $dt = $_POST["detail"];

  $sql = "SP_TICKETS_ADD '".$nr."','".$nm."','".$do."','".$ip."','".$pl."','".$dp."','".$ar."','".$eq."','".$mt."','".$dt."'";  
  $ticket = Consulta_dato($sql,$conn)['Ticket'] ;
  echo $ticket;

  //$to = 'Roberto.silos@sewsus.com.mx';  
  $to = 'SauloATRIS@sewsus.com.mx'; 
  $from = 'From: info.sistemas@sewsus.com.mx' . "\r\n";



  $subject = "Nuevo ticket de soporte - Sistemas ATR.";
  $message = 
 'El Usuario: '.$nm.' ('.$nr.') ha solicitado soporte.    
  User IP: '.$ip.'
  TICKET: '.$ticket.'
  
  DATOS DEL SOPORTE
  Planta: '.$pl.'
  Departamento: '.$dp.'
  Area: '.$ar.' 
  Equipo: '.substr_replace($eq,'http://',strpos($eq, '-')+2,0).' 
  Motivo: '.$mt.' 
  Detalle: '.$dt.'
  '; 

  echo $message;
  mail($to, $subject, $message, $from);

  echo'<script type="text/javascript">        
  alert("Ticket Generedo: '.$ticket.'");        
  window.location.href="ticket.php";        </script>';
}
else
{
  echo'<script type="text/javascript">        
  alert("Ticket de soporte no generado. Intentelo nuevamente.");        
  window.location.href="ticket.php";        </script>';
}
?>