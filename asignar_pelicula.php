<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $salaId = $_POST['sala_id'];
    $peliculaId = $_POST['pelicula'];

    // Consulta para actualizar la sala con la película seleccionada
    $sql = "UPDATE salas SET id_pelicula = ? WHERE id_salas = ?";
    $params = [$peliculaId, $salaId];
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        echo "Película asignada correctamente.";
        header("Location: taquilla.php");  // Redirige de vuelta a la página de taquilla
    }
}
?>
