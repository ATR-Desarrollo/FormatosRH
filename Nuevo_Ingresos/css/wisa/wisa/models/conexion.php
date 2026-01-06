<?php
/******************************************************  
     GENERA LA CADENA DE CONEXION Y REALIZA LA CONEXION
******************************************************/
$serverName = "172.30.75.11"; 
$connectionInfo = array("Database"=>"WDB_APPS","UID"=>"usrbdtmp","PWD"=>"12345678","CharacterSet"=>"UTF-8");
$connectionInfoERP = array("Database"=>"ERP","UID"=>"usrbdtmp","PWD"=>"12345678","CharacterSet"=>"UTF-8");
$connectionInfoERPDB = array("Database"=>"ERPDB","UID"=>"usrbdtmp","PWD"=>"12345678","CharacterSet"=>"UTF-8");
$conn = sqlsrv_connect( $serverName, $connectionInfo);
$connERP = sqlsrv_connect( $serverName, $connectionInfoERP);
$connERPDB = sqlsrv_connect( $serverName, $connectionInfoERPDB);
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

function ExecutaQuery($query,$cconex)
{
     try {
          $stmt = sqlsrv_prepare($cconex, $query, array());
          if (sqlsrv_execute($stmt) === false) {
               return $query;
          } else {
               $valor_devuelto = "1";
               return $valor_devuelto;
          }
     } catch (Exception $e) {
          return $query;
     }
}

?>