<?php
include 'conexion.php'; // Incluir el archivo de conexión

// Verificar si se ha enviado un término de búsqueda
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Consulta SQL para buscar en Productos
$sql = "SELECT * FROM Productos";
if (!empty($searchTerm)) {
    $sql .= " WHERE nombre_producto LIKE ? OR descripcion LIKE ?";
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
            <th>Nombre Proveedor</th>
            <th>Descripción</th>
            <th>Fecha de Creación</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id_proveedor']); ?></td>
                <td><?php echo htmlspecialchars($row['nombre_proveedor']); ?></td>
                <td><?php echo htmlspecialchars($row['contacto']); ?></td>
                <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                <td><?php echo htmlspecialchars($row['rfc']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td class="text-center">
                    <a href="edit_proveedor.php?id=<?php echo $row['id_proveedor']; ?>" class="btn btn-default" title="Modify">
                        <i class="fa fa-pencil"></i>
                    </a>
                    <a href="eliminar_proveedor.php?id=<?php echo $row['id_proveedor']; ?>" class="btn btn-default" title="Delete">
                        <i class="fa fa-remove"></i>
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>