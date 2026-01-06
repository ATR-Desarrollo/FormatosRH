<?php
    include "../conexion.php";

    $carpeta = $_GET['nombreCarpeta'];
    $planta = $_GET['planta'];
    $depto = $_GET['depto'];

    $sql = " [SP_SCCIS_INFO_CARPETA] '".$carpeta."', '".$planta."', '".$depto."'";
    $proc_carpeta = sqlsrv_prepare($conn, $sql);
    sqlsrv_execute($proc_carpeta);
    $infCarp=sqlsrv_fetch_array($proc_carpeta, SQLSRV_FETCH_ASSOC);

    if ($infCarp['Responsable'] != "") {
        echo 1;
    } else {
        echo 0;
    }
?>