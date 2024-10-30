<?php
session_start();
include 'conexion.php'; // Asegúrate de que este archivo contenga la lógica de conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_proveedor = $_POST['nombre_proveedor'];
    $contacto = $_POST['contacto'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];

    // Verificar si el proveedor ya existe
    $sql_verificar_proveedor = "SELECT id_proveedor FROM Proveedor WHERE nombre_proveedor = ?";
    $stmt_verificar = sqlsrv_query($conn, $sql_verificar_proveedor, array($nombre_proveedor));

    if ($stmt_verificar === false) {
        $_SESSION['status'] = ['message' => "Error al verificar proveedor: " . print_r(sqlsrv_errors(), true), 'type' => 'error'];
        header("Location: alta_almacen.php");
        exit();
    }

    $row_proveedor = sqlsrv_fetch_array($stmt_verificar, SQLSRV_FETCH_ASSOC);
    if ($row_proveedor) {
        $_SESSION['status'] = ['message' => "El proveedor ya existe: " . $nombre_proveedor, 'type' => 'error'];
        header("Location: alta_almacen.php");
        exit();
    }

    // Insertar el nuevo proveedor
    $sql_proveedor = "INSERT INTO Proveedor (nombre_proveedor, contacto, telefono, email) VALUES (?, ?, ?, ?)";
    $params_proveedor = array($nombre_proveedor, $contacto, $telefono, $email);
    $stmt_proveedor = sqlsrv_query($conn, $sql_proveedor, $params_proveedor);

    if ($stmt_proveedor === false) {
        $_SESSION['status'] = ['message' => "Error al agregar proveedor: " . print_r(sqlsrv_errors(), true), 'type' => 'error'];
        header("Location: alta_almacen.php");
        exit();
    }

    $_SESSION['status'] = ['message' => "Proveedor agregado exitosamente.", 'type' => 'success'];
    header("Location: alta_almacen.php");
    exit();
}
?>
