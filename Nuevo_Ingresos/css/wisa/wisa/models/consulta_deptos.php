<?php
    include "conexion.php";

    $Planta = $_GET['planta'];

    $sql = "SELECT DISTINCT(Departamento) FROM [SCCIS_mstCarpetas] WHERE Planta = ?";
    $params=array($Planta);

    $stmt = sqlsrv_query($conn, $sql, $params);

    $info = array();
    while ($row=sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $info[] = $row;
    }

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);

    echo json_encode($info);
