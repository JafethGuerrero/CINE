<?php
session_start();
include("conexion.php");

// Verificamos que se reciba el ID
if (isset($_GET['id'])) {
    $id_limpieza = $_GET['id'];

    // Obtener empleados disponibles
    $sqlEmpleados = "SELECT id_empleado, nombre FROM CINE.dbo.Empleados";
    $resultEmpleados = sqlsrv_query($conn, $sqlEmpleados);

    if ($resultEmpleados === false) {
        die("Error en la consulta de empleados: " . print_r(sqlsrv_errors(), true));
    }
} else {
    die("ID de limpieza no especificado.");
}
?>

<html>
<head>
    <title>Cambiar Empleado</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Cambiar Empleado de Limpieza</h1>
        <form action="guardar_cambio_empleado.php" method="POST">
            <input type="hidden" name="id_limpieza" value="<?php echo htmlspecialchars($id_limpieza); ?>">
            <div class="mb-3">
                <label for="empleado" class="form-label">Selecciona el nuevo empleado:</label>
                <select id="empleado" name="empleado" class="form-select">
                    <?php while ($empleado = sqlsrv_fetch_array($resultEmpleados, SQLSRV_FETCH_ASSOC)): ?>
                        <option value="<?php echo htmlspecialchars($empleado['id_empleado']); ?>">
                            <?php echo htmlspecialchars($empleado['nombre']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>
