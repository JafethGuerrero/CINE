<?php
include 'conexion.php'; // Incluir el archivo de conexión

// Verificar si se ha enviado un término de búsqueda
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Modificar la consulta SQL para incluir la búsqueda
$sql = "SELECT * FROM empleados";
if (!empty($searchTerm)) {
    $sql .= " WHERE nombre LIKE ? OR puesto LIKE ?";
}

// Preparar y ejecutar la consulta
$params = [];
if (!empty($searchTerm)) {
    $params = ["%$searchTerm%", "%$searchTerm%"];
}
$stmt = sqlsrv_query($conn, $sql, $params);
?>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Puesto</th>
            <th>Contraseña</th>
            <th>Fecha de Contratación</th>
            <th>Fecha Baja</th>
            <th>Salario</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
            <tr>
                <td><?php echo $row['id_empleado']; ?></td>
                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                <td><?php echo htmlspecialchars($row['puesto']); ?></td>
                <td><?php echo isset($row['contraseña']) ? htmlspecialchars($row['contraseña']) : 'No disponible'; ?></td>
                <td><?php echo $row['fecha_contratacion'] ? $row['fecha_contratacion']->format('Y-m-d') : 'No disponible'; ?></td>
                <td><?php echo $row['fecha_baja'] ? $row['fecha_baja']->format('Y-m-d') : 'No disponible'; ?></td>
                <td><?php echo $row['salario']; ?></td>
                <td class="text-center">
                    <a href="edit_empleado.php?id=<?php echo $row['id_empleado']; ?>" class="btn btn-default" title="Modificar">
                        <i class="fa fa-pencil"></i>
                    </a>
                    <a href="eliminar_empleados.php?id=<?php echo $row['id_empleado']; ?>" class="btn btn-default" title="Eliminar">
                        <i class="fa fa-remove"></i>
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
