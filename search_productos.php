<?php
include 'conexion.php'; // Incluir conexión

// Obtener parámetros de búsqueda y paginación
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Configurar el límite de registros por página
$limit = 10;
$offset = ($page - 1) * $limit;

// Modificar la consulta SQL
$sql = "
    SELECT p.id_producto, p.nombre_producto, p.descripcion, p.fecha_creacion, p.fecha_caducidad, c.nombre_categoria
    FROM Productos p
    LEFT JOIN Categorias c ON p.id_categoria = c.id_categoria
";
$params = [];
if (!empty($searchTerm)) {
    $sql .= " WHERE p.nombre_producto LIKE ? OR p.descripcion LIKE ?";
    $params = ["%$searchTerm%", "%$searchTerm%"];
}
$sql .= " ORDER BY p.id_producto OFFSET ? ROWS FETCH NEXT ? ROWS ONLY";
array_push($params, $offset, $limit);

// Ejecutar la consulta principal
$stmt = sqlsrv_query($conn, $sql, $params);

// Contar el total de registros
$sqlCount = "SELECT COUNT(*) AS total FROM Productos p LEFT JOIN Categorias c ON p.id_categoria = c.id_categoria";
if (!empty($searchTerm)) {
    $sqlCount .= " WHERE p.nombre_producto LIKE ? OR p.descripcion LIKE ?";
}

$stmtCount = sqlsrv_query($conn, $sqlCount, array_slice($params, 0, -2));
$totalRows = sqlsrv_fetch_array($stmtCount, SQLSRV_FETCH_ASSOC)['total'];
$totalPages = ceil($totalRows / $limit);
?>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre Producto</th>
            <th>Descripción</th>
            <th>Fecha Creación</th>
            <th>Fecha Caducidad</th>
            <th>Categoria</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
            <tr>
                <td><?php echo $row['id_producto']; ?></td>
                <td><?php echo htmlspecialchars($row['nombre_producto']); ?></td>
                <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                <td><?php echo $row['fecha_creacion'] ? $row['fecha_creacion']->format('Y-m-d') : 'No disponible'; ?></td>
                <td><?php echo $row['fecha_caducidad'] ? $row['fecha_caducidad']->format('Y-m-d') : 'No disponible'; ?></td>
                <td><?php echo htmlspecialchars($row['nombre_categoria']); ?></td>
                <td class="text-center">
                    <a href="edit_producto.php?id=<?php echo $row['id_producto']; ?>" class="btn btn-default" title="Modificar">
                        <i class="fa fa-pencil"></i>
                    </a>
                    <a href="eliminar_producto.php?id=<?php echo $row['id_producto']; ?>" class="btn btn-default" title="Eliminar">
                        <i class="fa fa-remove"></i>
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<!-- Paginación -->
<nav>
    <ul class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($searchTerm); ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>
