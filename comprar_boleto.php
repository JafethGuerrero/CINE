<?php
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar si se enviaron los parámetros necesarios
    if (!isset($_POST['idCliente']) || !isset($_POST['idSalas']) || !isset($_POST['selectedSeats']) || !isset($_POST['cantidadAsientos'])) {
        echo json_encode(['message' => 'Datos incompletos.']);
        exit;
    }

    $idCliente = $_POST['idCliente'];
    $idSalas = $_POST['idSalas'];
    $selectedSeats = $_POST['selectedSeats']; // String con los asientos seleccionados
    $cantidadAsientos = $_POST['cantidadAsientos']; // Cantidad de asientos seleccionados
    $selectedSeatsArray = explode(',', $selectedSeats); // Convertir en array de asientos seleccionados

    // Comenzar transacción para insertar la compra
    sqlsrv_begin_transaction($conn);

    try {
        // Generar un ID único para el boleto (puedes usar un valor generado automáticamente por SQL Server si prefieres)
        $idBoleto = uniqid("BOL");

        // Insertar la compra en la tabla 'Boleto'
        $sql = "INSERT INTO Boleto (id_boleto, fecha, id_salas, cantidad_asientos, numero_asientos) 
                VALUES (?, GETDATE(), ?, ?, ?)";
        $params = [$idBoleto, $idSalas, $cantidadAsientos, $selectedSeats]; // Insertar los asientos seleccionados como cadena
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            throw new Exception("Error al insertar la compra del boleto.");
        }

        // Marcar los asientos como ocupados
        foreach ($selectedSeatsArray as $seat) {
            $sqlUpdate = "UPDATE asientos SET estado = 'ocupado' WHERE id_asiento = ?";
            $stmtUpdate = sqlsrv_query($conn, $sqlUpdate, [$seat]);

            if ($stmtUpdate === false) {
                throw new Exception("Error al actualizar el estado del asiento.");
            }
        }

        // Confirmar transacción
        sqlsrv_commit($conn);

        // Responder con éxito
        echo json_encode(['message' => 'Compra realizada con éxito.']);
    } catch (Exception $e) {
        // Si ocurre un error, deshacer la transacción
        sqlsrv_rollback($conn);

        // Enviar mensaje de error
        echo json_encode(['message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['message' => 'Método no permitido.']);
}
?>
