<?php
session_start();
include 'conexion.php'; // Asegúrate de que este archivo contenga la lógica de conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_producto = $_POST['nombre_producto'];
    $descripcion = $_POST['descripcion'];
    $fecha_caducidad = $_POST['fecha_caducidad']; // Nuevo campo

    // Verificar si el producto ya existe
    $sql_verificar_producto = "SELECT id_producto FROM Productos WHERE nombre_producto = ?";
    $stmt_verificar = sqlsrv_query($conn, $sql_verificar_producto, array($nombre_producto));

    if ($stmt_verificar === false) {
        $_SESSION['status'] = ['message' => "Error al verificar producto: " . print_r(sqlsrv_errors(), true), 'type' => 'error'];
        header("Location: alta_producto.php");
        exit();
    }

    $row_producto = sqlsrv_fetch_array($stmt_verificar, SQLSRV_FETCH_ASSOC);
    if ($row_producto) {
        $_SESSION['status'] = ['message' => "El producto ya existe: " . $nombre_producto, 'type' => 'error'];
        header("Location: alta_producto.php");
        exit();
    }

    // Insertar el nuevo producto
    $sql_producto = "EXEC sp_add_producto ?, ?, ?";
    $params_producto = array($nombre_producto, $descripcion, $fecha_caducidad); // Incluir fecha de caducidad
    $stmt_producto = sqlsrv_query($conn, $sql_producto, $params_producto);

    if ($stmt_producto === false) {
        $_SESSION['status'] = ['message' => "Error al agregar producto: " . print_r(sqlsrv_errors(), true), 'type' => 'error'];
        header("Location: alta_producto.php");
        exit();
    }

    $_SESSION['status'] = ['message' => "Producto agregado exitosamente.", 'type' => 'success'];
    header("Location: alta_producto.php");
    exit();
}
?>
