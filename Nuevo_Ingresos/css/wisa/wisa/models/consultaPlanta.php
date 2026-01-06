<?php
    include "conexion.php";
    $param = array();
    $options=array("Scrollable" => SQLSRV_CURSOR_KEYSET);

    $sql = $_GET['query'];
    
    $SRV = $_GET['srv'];
    $DB = $_GET['db'];
    $UID = $_GET['uid'];
    $PSW = $_GET['psw'];

    $connPlanta = sqlsrv_connect($SRV, array("Database"=>$DB, "Uid"=>$UID, "PWD"=>$PSW));

    $stmt = sqlsrv_query($connPlanta, $sql, $param, $options);

    $info = array();
    while ($row=sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $info[] = $row;
    }

    echo json_encode($info);
?>