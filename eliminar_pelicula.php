<?php
include 'conexion.php'; // Incluir el archivo de conexión
include 'header.php'; // Incluir el encabezado

// Verificar si el ID de la película está en la URL
if (isset($_GET['id'])) {
    $id_pelicula = $_GET['id'];

    // Confirmar la eliminación de la película
    echo "<script>
            if (confirm('¿Estás seguro de que deseas eliminar esta película?')) {
                window.location.href = 'eliminar_pelicula.php?id_confirmado=" . $id_pelicula . "';
            } else {
                window.location.href = 'cartelera.php';
            }
          </script>";
}

// Verificar si se ha confirmado la eliminación
if (isset($_GET['id_confirmado'])) {
    $id_pelicula_confirmado = $_GET['id_confirmado'];

    // Ejecutar el procedimiento almacenado para eliminar la película
    $sql = "EXEC EliminarCartelera ?";
    $params = array($id_pelicula_confirmado);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt) {
        echo "<script>alert('Película eliminada exitosamente.'); window.location.href='cartelera.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar la película.'); window.location.href='cartelera.php';</script>";
    }
}

include 'footer.php'; // Incluir el footer
?>
