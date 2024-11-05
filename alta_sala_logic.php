<?php
include("conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $cantidad_asientos = $_POST['cantidad_asientos'];
    $tipo_proyeccion = $_POST['tipo_proyeccion'];
    $id_pelicula = $_POST['id_pelicula']; // Obtiene el valor de id_pelicula

    // Si no se selecciona una película, asigna NULL
    if (empty($id_pelicula)) {
        $id_pelicula = null; // Asigna NULL si no se seleccionó una película
    }

    // Usar el procedimiento almacenado para insertar una nueva sala
    $query = "EXEC InsertarSala ?, ?, ?, ?, ?";
    $params = array($nombre, $cantidad_asientos, $tipo_proyeccion, NULL, $id_pelicula); // Pasar NULL si no hay película

    // Pasar NULL como un parámetro de tipo SQLSRV
    $result = sqlsrv_query($conn, $query, $params);

    if ($result) {
        header("Location: salas.php?success=1");
        exit;
    } else {
        echo "Error al registrar la sala: " . print_r(sqlsrv_errors(), true);
    }
}
?>
