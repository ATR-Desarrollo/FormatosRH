<?php
    include "conexion.php";
    $param = array();
    $options=array("Scrollable" => SQLSRV_CURSOR_KEYSET);

    $sql = $_GET['query'];
    $stmt = sqlsrv_query($connERPDB, $sql, $param, $options);

    $info = array();
    while ($row=sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $info[] = $row;
    }

    echo json_encode($info);
?>