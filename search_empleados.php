<?php
include 'conexion.php'; // Incluir conexión

// Obtener parámetros de búsqueda y paginación
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Configurar el límite de registros por página
$limit = 10;
$offset = ($page - 1) * $limit;

// Modificar la consulta SQL
$sql = "SELECT * FROM empleados";
$params = [];
if (!empty($searchTerm)) {
    $sql .= " WHERE nombre LIKE ? OR puesto LIKE ? OR salario LIKE ?";
    $params = ["%$searchTerm%", "%$searchTerm%", "%$searchTerm%"];
}
$sql .= " ORDER BY id_empleado OFFSET ? ROWS FETCH NEXT ? ROWS ONLY";
array_push($params, $offset, $limit);

// Ejecutar la consulta principal
$stmt = sqlsrv_query($conn, $sql, $params);

// Contar el total de registros
$sqlCount = "SELECT COUNT(*) AS total FROM empleados";
if (!empty($searchTerm)) {
    $sqlCount .= " WHERE nombre LIKE ? OR puesto LIKE ? OR salario LIKE ?";
}
$stmtCount = sqlsrv_query($conn, $sqlCount, array_slice($params, 0, -2));
$totalRows = sqlsrv_fetch_array($stmtCount, SQLSRV_FETCH_ASSOC)['total'];
$totalPages = ceil($totalRows / $limit);
?>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Puesto</th>
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

<!-- Paginación -->
<nav>
    <ul class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                <a class="page-link" href="#" data-page="<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>
