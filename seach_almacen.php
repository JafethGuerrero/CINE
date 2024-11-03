<?php
include 'conexion.php'; // Incluir el archivo de conexión

// Verificar si se ha enviado un término de búsqueda
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Consulta SQL con JOIN para obtener el nombre del producto y el nombre del proveedor
$sql = "
    SELECT A.id_almacen, P.nombre_producto, PR.nombre_proveedor, A.ubicacion_producto, A.cantidad, 
           A.tipo_almacenamiento, A.fecha_reabastecimiento
    FROM Almacen A
    JOIN Productos P ON A.id_producto = P.id_producto
    JOIN Proveedor PR ON A.id_proveedor = PR.id_proveedor";

if (!empty($searchTerm)) {
    $sql .= " WHERE A.tipo_almacenamiento LIKE ? OR A.ubicacion_producto LIKE ? OR P.nombre_producto LIKE ? OR PR.nombre_proveedor LIKE ?";
}

// Preparar y ejecutar la consulta
$params = [];
if (!empty($searchTerm)) {
    $params = ["%$searchTerm%", "%$searchTerm%", "%$searchTerm%", "%$searchTerm%"];
}
$stmt = sqlsrv_query($conn, $sql, $params);
?>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Producto</th>
            <th>Proveedor</th>
            <th>Ubicación Producto</th>
            <th>Cantidad</th>
            <th>Tipo Almacenamiento</th>
            <th>Fecha Reabastecimiento</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id_almacen']); ?></td>
                <td><?php echo htmlspecialchars($row['nombre_producto']); ?></td>
                <td><?php echo htmlspecialchars($row['nombre_proveedor']); ?></td>
                <td><?php echo htmlspecialchars($row['ubicacion_producto']); ?></td>
                <td><?php echo htmlspecialchars($row['cantidad']); ?></td>
                <td><?php echo htmlspecialchars($row['tipo_almacenamiento']); ?></td>
                <td><?php echo htmlspecialchars($row['fecha_reabastecimiento'] ? $row['fecha_reabastecimiento']->format('Y-m-d') : 'N/A'); ?></td>
                <td class="text-center">
                    <a href="edit_almacen.php?id=<?php echo $row['id_almacen']; ?>" class="btn btn-default" title="Modificar">
                        <i class="fa fa-pencil"></i>
                    </a>
                    <a href="eliminar_almacen.php?id=<?php echo $row['id_almacen']; ?>" class="btn btn-default" title="Eliminar">
                        <i class="fa fa-remove"></i>
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
