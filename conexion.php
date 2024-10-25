<?php
$servername = "DRAGON_1208"; // Cambia esto según tu servidor
$conexion = array("Database"=>"CINE", "CharacterSet"=>"UTF-8");

$conn = sqlsrv_connect($servername, $conexion);
if ($conn) {
    // Conexión exitosa
} else {
    echo "<br><br>
        <div class='alert alert-danger alert-dismissible'>
            <strong>Error:</strong> Falló la conexión a la base de datos, contacte a su administrador.
        </div>";
    die(print_r(sqlsrv_errors(), true));
}
?>
