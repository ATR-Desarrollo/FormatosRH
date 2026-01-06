<?php
    include "conexion.php";
    // $data = $_GET['data'];
    $sql = $_GET['query'];

    $res = ExecutaQuery($sql,$conn); // Executa la consulta y regresa el valor de la consulta

    echo $res;
?>