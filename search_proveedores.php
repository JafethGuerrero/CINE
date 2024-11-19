<?php
include 'conexion.php'; // Incluir el archivo de conexión

// Verificar si se ha enviado un término de búsqueda
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Configurar el límite de registros por página
$limit = 10;
$offset = ($page - 1) * $limit;

// Modificar la consulta SQL para incluir el filtro de búsqueda
$sql = "
    SELECT pr.id_proveedor, pr.nombre_proveedor, pr.contacto, pr.telefono, pr.rfc, pr.email
    FROM Proveedor pr
";
$params = [];
if (!empty($searchTerm)) {
    $sql .= " WHERE pr.nombre_proveedor LIKE ? OR pr.contacto LIKE ? OR pr.telefono LIKE ? OR pr.rfc LIKE ? OR pr.email LIKE ?";
    $params = ["%$searchTerm%", "%$searchTerm%", "%$searchTerm%", "%$searchTerm%", "%$searchTerm%"];
}
$sql .= " ORDER BY pr.id_proveedor OFFSET ? ROWS FETCH NEXT ? ROWS ONLY"; // Corregido el alias 'p' por 'pr'
array_push($params, $offset, $limit);

// Ejecutar la consulta principal
$stmt = sqlsrv_query($conn, $sql, $params);

// Verificar si la consulta se ejecutó correctamente
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Contar el total de registros para la paginación
$sqlCount = "SELECT COUNT(*) AS total FROM Proveedor pr"; // Corregido el alias 'p' por 'pr'
if (!empty($searchTerm)) {
    $sqlCount .= " WHERE pr.nombre_proveedor LIKE ? OR pr.contacto LIKE ? OR pr.telefono LIKE ? OR pr.rfc LIKE ? OR pr.email LIKE ?";
}
$stmtCount = sqlsrv_query($conn, $sqlCount, array_slice($params, 0, -2));
if ($stmtCount === false) {
    die(print_r(sqlsrv_errors(), true));
}

$totalRows = sqlsrv_fetch_array($stmtCount, SQLSRV_FETCH_ASSOC)['total'];
$totalPages = ceil($totalRows / $limit); ?>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre Proveedor</th>
            <th>Contacto</th>
            <th>Teléfono</th>
            <th>RFC</th>
            <th>Email</th>
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
                    <a href="edit_proveedor.php?id=<?php echo $row['id_proveedor']; ?>" class="btn btn-default" title="Modificar">
                        <i class="fa fa-pencil"></i>
                    </a>
                    <a href="eliminar_proveedor.php?id=<?php echo $row['id_proveedor']; ?>" class="btn btn-default" title="Eliminar">
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
