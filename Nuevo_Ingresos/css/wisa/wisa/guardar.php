<?php
$archivo = $_FILES["archivo"];
$resultado = move_uploaded_file($archivo["tmp_name"], "Files/".$archivo["name"]);
if ($resultado) {
    echo "Subido con éxito";
    alert("4");
} else {
    echo "Error al subir archivo";
    alert("5");
}
