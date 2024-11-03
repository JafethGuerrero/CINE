<?php
session_start();
include("conexion.php");

// Verificamos que se reciba el ID
if (isset($_GET['id'])) {
    $id_limpieza = $_GET['id'];

    // Actualizar el estado a "Ensuciado"
    $sqlUpdate = "UPDATE CINE.dbo.Limpieza SET estado = 'Ensuciado' WHERE id_limpieza = ?";
    $params = array($id_limpieza);
    
    $stmt = sqlsrv_query($conn, $sqlUpdate, $params);
    
    if ($stmt === false) {
        die("Error en la actualización: " . print_r(sqlsrv_errors(), true));
    } else {
        // Redirigir de vuelta a la página de limpieza
        header("Location: limpieza.php");
        exit();
    }
} else {
    die("ID de limpieza no especificado.");
}
?>
