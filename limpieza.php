<?php
session_start();
include("header.php");
include("conexion.php");
include "footer.php"; // Incluimos el footer

// Consulta para obtener los datos de la vista, incluyendo los nombres de sala y empleado
$sqlLimpieza = "
    SELECT 
        L.id_limpieza,
        S.nombre AS nombre_sala,
        E.nombre AS nombre_empleado,
        L.id_empleado,
        L.estado
    FROM 
        CINE.dbo.Limpieza L
    JOIN 
        CINE.dbo.Salas S ON L.id_salas = S.id_salas
    JOIN 
        CINE.dbo.Empleados E ON L.id_empleado = E.id_empleado
"; 
$resultLimpieza = sqlsrv_query($conn, $sqlLimpieza);

// Verificar si la consulta se ejecutó correctamente
if ($resultLimpieza === false) {
    die("Error en la consulta: " . print_r(sqlsrv_errors(), true));
}
?>

<html>
<head>
    <title>Limpieza</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Limpieza de Salas</h1>
        <a href="asignar_limpieza.php" class="btn btn-primary mb-3">Asignar Limpieza</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre de Sala</th>
                    <th>Nombre de Empleado</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($limpieza = sqlsrv_fetch_array($resultLimpieza, SQLSRV_FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($limpieza['id_limpieza']); ?></td>
                        <td><?php echo htmlspecialchars($limpieza['nombre_sala']); ?></td>
                        <td>
                            <form action="guardar_cambio_empleado.php" method="POST" class="d-inline">
                                <input type="hidden" name="id_limpieza" value="<?php echo htmlspecialchars($limpieza['id_limpieza']); ?>">
                                <select name="empleado" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <?php 
                                    // Consulta para obtener empleados del puesto de limpieza en cada iteración
                                    $sqlEmpleados = "SELECT id_empleado, nombre FROM CINE.dbo.Empleados WHERE puesto = 'Limpieza'";
                                    $resultEmpleados = sqlsrv_query($conn, $sqlEmpleados);
                                    while ($empleado = sqlsrv_fetch_array($resultEmpleados, SQLSRV_FETCH_ASSOC)): ?>
                                        <option value="<?php echo htmlspecialchars($empleado['id_empleado']); ?>" 
                                            <?php echo ($empleado['id_empleado'] == $limpieza['id_empleado']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($empleado['nombre']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </form>
                        </td>
                        <td><?php echo htmlspecialchars($limpieza['estado']); ?></td>
                        <td>
                            <?php if ($limpieza['estado'] !== 'Limpiado'): ?>
                                <a href="cambiar_estado.php?id=<?php echo htmlspecialchars($limpieza['id_limpieza']); ?>" class="btn btn-success">Marcar como Limpiado</a>
                                <a href="volver_a_ensuciar.php?id=<?php echo htmlspecialchars($limpieza['id_limpieza']); ?>" class="btn btn-danger">Volver a Ensuciar</a>
                            <?php else: ?>
                                <span class="text-success">Limpiado</span>
                                <a href="volver_a_ensuciar.php?id=<?php echo htmlspecialchars($limpieza['id_limpieza']); ?>" class="btn btn-danger">Volver a Ensuciar</a>
                            <?php endif; ?>
                            <a href="eliminar_limpieza.php?id=<?php echo htmlspecialchars($limpieza['id_limpieza']); ?>" class="btn btn-danger">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
