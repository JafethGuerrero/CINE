<?php
include("conexion.php");

if (isset($_GET['id'])) {
    $id_limpieza = $_GET['id'];

    // Consulta para eliminar la limpieza
    $sqlEliminar = "DELETE FROM CINE.dbo.Limpieza WHERE id_limpieza = ?";
    $params = array($id_limpieza);
    $stmt = sqlsrv_query($conn, $sqlEliminar, $params);

    if ($stmt === false) {
        die("Error al eliminar limpieza: " . print_r(sqlsrv_errors(), true));
    }

    // Redireccionar de regreso a la página de limpieza
    header("Location: limpieza.php");
    exit;
}
?>
