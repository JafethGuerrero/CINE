<?php
include 'conexion.php'; // Incluir el archivo de conexión

// Verificar si se ha enviado un término de búsqueda
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Preparar la consulta dependiendo del término de búsqueda
if (empty($searchTerm)) {
    // Llamar al procedimiento almacenado para obtener todos los productos
    $sql = "EXEC sp_get_all_products";
    $params = null; // No se necesitan parámetros
} else {
    // Llamar al procedimiento almacenado para buscar productos
    $sql = "EXEC sp_search_products ?";
    $params = array($searchTerm);
}

// Ejecutar el procedimiento almacenado
$stmt = sqlsrv_query($conn, $sql, $params);
?>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre Producto</th>
            <th>Descripcion</th>
            <th>Fecha Creación</th>
            <th>Fecha Caducidad</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id_producto']); ?></td>
                <td><?php echo htmlspecialchars($row['nombre_producto']); ?></td>
                <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                <td><?php echo htmlspecialchars($row['fecha_creacion'] ? $row['fecha_creacion']->format('Y-m-d') : 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['fecha_caducidad'] ? $row['fecha_caducidad']->format('Y-m-d') : 'N/A'); ?></td>
                <td class="text-center">
                    <a href="edit_producto.php?id=<?php echo $row['id_producto']; ?>" class="btn btn-default" title="Modify">
                        <i class="fa fa-pencil"></i>
                    </a>
                    <a href="eliminar_producto.php?id=<?php echo $row['id_producto']; ?>" class="btn btn-default" title="Delete">
                        <i class="fa fa-remove"></i>
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
