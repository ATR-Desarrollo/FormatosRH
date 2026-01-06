<?php
header("Content-Type: text/html;charset=utf-8");

/******************************************************  
    * EL INCLUDE CONEXION IMPORTA TODO EL CODIGO Y FUNCIONES DE CENEXION.PHP
    * NESESARIAS EN LA MAYORIA DE LOS MODULOS
    ******************************************************/
    include("conexion.php");
  
    /******************************************************  
    * SI NO ESTA INICIADA UNA SESION, LA INICIALIZA
    ******************************************************/
  if(session_start())
  {


    



    /******************************************************  
    * REVISA SI EXISTE UN USUARIO LOGEADO, EN CASO DE QUE NO DEVUELVE A LA PAGINA LOGIN.
    * ESTE MODULO DEBE SER INCLUIDO EN TODAS LAS PAGINAS PHP
    * PARA NO PERMITIR USUARIOS NO LOGEADOS EN ALGUNAS SECCIONES DE LA PAGINA.
    *****************************************************
    */
    if (isset( $_SESSION['Usuario']))
    { 
      $User= $_SESSION['Usuario'];
      $UserType= "ADMIN";
    }
    else{
      $UserType= "";
      /*
      echo'<script type="text/javascript">
        alert("No se ha logeado correctamente, vuelelva a intentarlo");
        window.location.href="login.php";
        </script>';*/
    }
  }
  else{  $UserType= ""; }
?>