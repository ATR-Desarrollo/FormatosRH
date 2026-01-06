<?php
include("../conexion.php"); // ajusta la ruta

if (isset($_GET['query'])) {
    $query = $_GET['query'];

    $stmt = sqlsrv_query($conn, $query);
    if ($stmt === false) {
        echo json_encode(["error" => sqlsrv_errors()]);
        exit;
    }

    $result = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $result[] = $row;
    }

    echo json_encode($result); // <-- salida en JSON
}
?>
