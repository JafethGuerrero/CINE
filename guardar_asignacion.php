<?php
header('Content-Type: application/json');
include 'conexion.php';

try {
    // Obtener los datos POST
    $salaId = $_POST['salaId'];
    $peliculaId = $_POST['peliculaId'];
    $horario = $_POST['horario'];

    // Actualizar la película en la sala
    $sqlPelicula = "{CALL actualizar_pelicula_sala(?, ?)}";
    $paramsPelicula = array($salaId, $peliculaId);
    $stmtPelicula = sqlsrv_query($conn, $sqlPelicula, $paramsPelicula);

    // Actualizar el horario en cartelera
    $sqlHorario = "{CALL actualizar_horario(?, ?)}";
    $paramsHorario = array($peliculaId, $horario);
    $stmtHorario = sqlsrv_query($conn, $sqlHorario, $paramsHorario);
    
    if ($stmtPelicula === false || $stmtHorario === false) {
        throw new Exception(print_r(sqlsrv_errors(), true));
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Película y horario actualizados correctamente'
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>