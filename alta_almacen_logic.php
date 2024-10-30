<?php
session_start();
include 'conexion.php'; // Asegúrate de que este archivo contenga la lógica de conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ubicacion_producto = $_POST['ubicacion_producto'];
    $cantidad = $_POST['cantidad'];
    $tipo_almacenamiento = $_POST['tipo_almacenamiento'];
    $fecha_reabastecimiento = $_POST['fecha_reabastecimiento'];

    // Asegúrate de que el id_producto y id_proveedor se obtengan correctamente, aquí un ejemplo simple
    $id_producto = 1; // Cambia este valor por el id_producto correspondiente
    $id_proveedor = 1; // Cambia este valor por el id_proveedor correspondiente

    // Insertar el registro en la tabla Almacen
    $sql_almacen = "INSERT INTO Almacen (id_producto, id_proveedor, ubicacion_producto, cantidad, tipo_almacenamiento, fecha_reabastecimiento) VALUES (?, ?, ?, ?, ?, ?)";
    $params_almacen = array($id_producto, $id_proveedor, $ubicacion_producto, $cantidad, $tipo_almacenamiento, $fecha_reabastecimiento);
    $stmt_almacen = sqlsrv_query($conn, $sql_almacen, $params_almacen);

    if ($stmt_almacen === false) {
        $_SESSION['status'] = ['message' => "Error al agregar al almacén: " . print_r(sqlsrv_errors(), true), 'type' => 'error'];
        header("Location: alta_almacen.php");
        exit();
    }

    $_SESSION['status'] = ['message' => "Registro agregado al almacén exitosamente.", 'type' => 'success'];
    header("Location: alta_almacen.php");
    exit();
}
?>
