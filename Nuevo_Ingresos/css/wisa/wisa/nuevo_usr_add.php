<meta http-equiv="Content-Type" content="text/html" charset="utf-8">
<?php
//
session_start();
 

include("conexion.php");

  
$conn = sqlsrv_connect( $serverName, $connectionInfo);
if(!empty($_POST))
{
    $usua=$_POST['usua'];
    $nomb=$_POST['nomb'];
    $corr=$_POST['corr'];
    $nore=$_POST['nume'];
    $pass=$_POST['pass'];
    $pass2=$_POST['pas2'];
    $tipo=2;

    $plan=$_POST['plan'];
    $depa=$_POST['depa'];

    if ($pass == $pass2 )
  {

    $sql =" SELECT * from  Usuarios where noRELOJ=".$nore;
    $params=array();
    $options=array("Scrollable" => SQLSRV_CURSOR_KEYSET );
  
   $stmt = sqlsrv_query( $conn, $sql , $params, $options );
   $row_count = sqlsrv_num_rows( $stmt );
   $row=sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);

    if ($row_count === false) { // SI NO EXISTE EL no ctrl, MANDA MENSAJE DE ERROR             
      echo'<script type="text/javascript">      alert("Error al consultar");      window.location.href="nuevo_usr.php";
      </script>';           }
    else    {
      if($row_count > 0)        { //si existe el usuario actualiza los datos

        $sql = "update USUARIOS set password =?, correo=?, Nombre=?, tipo=? where usuario = ? ";
        $proc_result = sqlsrv_prepare($conn, $sql,array(&$pass,&$corr,&$nomb,&$tipo,&$usua));
        sqlsrv_execute($proc_result);

        echo'<script type="text/javascript">        alert("Datos actualizados");
        window.location.href="login.php";        </script>';
      }
      else
      { 
        $sql = "INSERT into  USUARIOS values(?,?,?,?,?,?,?,?) ";
        $proc_result = sqlsrv_prepare($conn, $sql,array(&$nore,&$nomb,&$corr,&$usua,&$pass,&$tipo,&$plan,&$depa));
          sqlsrv_execute($proc_result);
    
        echo'<script type="text/javascript">
        alert("Usuario registrado");
        window.location.href="login.php";
        </script>';

       } 
    
    }
  }
  else
  {
               echo'<script type="text/javascript">
                    alert("Password no coinciden favor de revisar");
                    window.location.href="nuevo_usr.php?u='.$usua.'&n='.$nore.'";
                    </script>';
  }


}
else {
  
  echo'<script type="text/javascript">
            alert("Error al recibir datos");
            window.location.href="nuevo_usr.php";
            </script>';    
}

?>
 