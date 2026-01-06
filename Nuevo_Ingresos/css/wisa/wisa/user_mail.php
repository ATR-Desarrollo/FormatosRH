<meta http-equiv="Content-Type" content="text/html" charset="utf-8">
<?php
//
session_start();
header('Content-Type: text/html; charset=UTF-8');
$NoContol = "";
   include("conexion.php");
$conn = sqlsrv_connect( $serverName, $connectionInfo);
if(!empty($_POST))
{
    $usuario=$_POST['correo'];

    $sql = " SELECT * FROM [Usuarios] WHERE correo =  '$usuario'"; 
    $params=array();
    $options=array("Scrollable" => SQLSRV_CURSOR_KEYSET );

$stmt = sqlsrv_query( $conn, $sql , $params, $options );
$row_count = sqlsrv_num_rows( $stmt );
$row=sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);

if ($row_count === false) { // SI NO EXISTE EL no ctrl, MANDA MENSAJE DE ERROR             
        echo'<script type="text/javascript">
        alert("Correo no existe en tabla de usuarios");
        window.location.href="login.php";
        </script>';           }
    else    {
      if($row_count > 0)        { 

        $to = $usuario;
        $subject = "Sistema Evaluaciones - Recuperacion de clave de acceso.";
        $message = "Ha solicitado la recuperacion de su password para el sistema de evaluaciones, favor de entrar en el siguiente link para generar uno nuevo:
        http://smatrsaulocal:8080/apps/atrpaw/recuperar.php?u=".$row['Usuario'];
        $from = 'From: info.sistemas@sewsus.com.mx' . "\r\n";
        ini_set('SMTP' ,'172.30.55.74');
        //mail($to, $subject, $message, '','info.sistemas@sewsus.com.mx');
        mail($to, $subject, $message, $from);
        echo'<script type="text/javascript">
        alert("Correo enviado ");
        window.location.href="login.php";
        </script>';   
      }
        else
        {echo'<script type="text/javascript">
          alert("Correo no esta registrado");
          window.location.href="login.php";
          </script>';  }
    }
}
else {  
  echo'<script type="text/javascript">
            alert("Error al recibir el correo");
            window.location.href="login.php";
            </script>';    
}

?>
 