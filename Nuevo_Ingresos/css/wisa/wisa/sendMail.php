<?php 
include "conexion.php";

if (isset($_POST['btnRespSis'])) {
    echo "Correo a encargado de sistemas enviado";
    sendMail($conn,"SCC25-".$_POST['fSis']);
}

if (isset($_POST['btnRespCC'])) {
    echo "Correo a responsable de carpeta enviado";
    sendMailRespCC($conn, "SCC25-".$_POST['fCC']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <form action="sendMail.php" method="post">
            <h1>Correo a encargado de sistemas</h1>
            SCC25- <input type="text" name="fSis" id="fSis">
            <input type="submit" value="Mandar mail" name="btnRespSis">
        </form>
    </div>

    <div>
        <form action="sendMail.php" method="post">
            <h1>Correo a responsable de carpeta</h1>
            SCC25- <input type="text" name="fCC" id="fCC">
            <input type="submit" value="Mandar mail" name="btnRespCC">
        </form>
    </div>
</body>
</html>
