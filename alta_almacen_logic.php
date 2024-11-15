<?php
include("conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cantidad = (int)$_POST['cantidad']; // Aseguramos que cantidad sea entero
    $tipo_almacenamiento = strtoupper($_POST['tipo_almacenamiento']); // Convertimos a mayúsculas
    $fecha_reabastecimiento = $_POST['fecha_reabastecimiento'];
    $id_producto = $_POST['id_producto']; // Mantener como string (nvarchar)
    $id_proveedor = $_POST['id_proveedor']; // Mantener como string (nvarchar)

    // Usar el procedimiento almacenado para insertar un nuevo almacén
    $query = "EXEC sp_add_almacen ?, ?, ?, ?, ?";
    $params = array($cantidad, $tipo_almacenamiento, $fecha_reabastecimiento, $id_producto, $id_proveedor);

    $result = sqlsrv_query($conn, $query, $params);

    if ($result) {
        header("Location: almacen.php?success=1");
        exit;
    } else {
        echo "Error al registrar el almacén: " . print_r(sqlsrv_errors(), true);
    }
}
