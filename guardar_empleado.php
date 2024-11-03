<?php
session_start();
include("conexion.php");

// Verificamos que se reciba el ID y el nuevo empleado
if (isset($_POST['id_limpieza']) && isset($_POST['empleado'])) {
    $id_limpieza = $_POST['id_limpieza'];
    $nuevo_empleado = $_POST['empleado'];

    // Actualizar el empleado en la limpieza
    $sqlUpdate = "UPDATE CINE.dbo.Limpieza SET id_empleado = ? WHERE id_limpieza = ?";
    $params = array($nuevo_empleado, $id_limpieza);
    
    $stmt = sqlsrv_query($conn, $sqlUpdate, $params);
    
    if ($stmt === false) {
        die("Error en la actualización del empleado: " . print_r(sqlsrv_errors(), true));
    } else {
        // Redirigir de vuelta a la página de limpieza
        header("Location: limpieza.php");
        exit();
    }
} else {
    die("ID de limpieza o empleado no especificado.");
}
?>
