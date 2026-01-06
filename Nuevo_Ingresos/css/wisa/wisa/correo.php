<?php 
include "conexion.php";

if (isset($_POST['btnRespSis'])) {
    $to = $_POST['fCC'];
    
    $from = 'Content-Type: text/html; charset=UTF-8' . "\r\n". 'From: <notificaciones.sistemas@sewsus.com.mx>' . "\r\n". 'Bcc: jesus.teran@sewsus.com.mx' . "\r\n";
    $subject = $_POST['fTitulo'];
    $message = $_POST['fMessage'];

    $message = nl2br($message);

    mail($to, $subject, $message, $from);
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
        <form action="correo.php" method="post">
            <h1>Correo</h1>
            Para: <input type="text" name="fCC" id="fCC"> (Separa por comas)
            Titulo: <input name="fTitulo" id="fTitulo">
            Mensaje: <textarea type="text" name="fMessage" id="fMessage"></textarea>
            <input type="submit" value="Mandar mail" name="btnRespSis">
        </form>
    </div>

</body>
</html>
