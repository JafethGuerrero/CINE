<?php
include 'conexion.php';

// Obtener el término de búsqueda y la página actual desde los parámetros de la solicitud
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10; // Número de registros por página
$offset = ($page - 1) * $limit;

// Construir la consulta con filtros
$sql = "
    SELECT id_cliente, nombre, correo_electronico, celular, cuenta_bancaria
    FROM clientes
";

$params = [];
if (!empty($searchTerm)) {
    $sql .= " WHERE nombre LIKE ? 
              OR correo_electronico LIKE ? 
              OR celular LIKE ? 
              OR cuenta_bancaria LIKE ?";
    $params = ["%$searchTerm%", "%$searchTerm%", "%$searchTerm%", "%$searchTerm%"];
}

$sql .= " ORDER BY id_cliente OFFSET ? ROWS FETCH NEXT ? ROWS ONLY";
$params[] = $offset;
$params[] = $limit;

$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Generar el HTML de los resultados
$html = '';
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $html .= "<tr>
                <td>{$row['id_cliente']}</td>
                <td>{$row['nombre']}</td>
                <td>{$row['correo_electronico']}</td>
                <td>{$row['celular']}</td>
                <td>{$row['cuenta_bancaria']}</td>
                <td class='text-center'>
                    <a href='edit_cliente.php?id={$row['id_cliente']}' class='btn btn-warning btn-sm' title='Modificar'>
                        <i class='fas fa-edit'></i>
                    </a>
                    <a href='eliminar_cliente.php?id={$row['id_cliente']}' class='btn btn-danger btn-sm' title='Eliminar'>
                        <i class='fas fa-trash-alt'></i>
                    </a>
                </td>
              </tr>";
}

// Consulta para obtener el total de registros
$sqlTotal = "
    SELECT COUNT(*) AS total
    FROM clientes
";

if (!empty($searchTerm)) {
    $sqlTotal .= " WHERE nombre LIKE ? 
                   OR correo_electronico LIKE ? 
                   OR celular LIKE ? 
                   OR cuenta_bancaria LIKE ?";
    $paramsTotal = ["%$searchTerm%", "%$searchTerm%", "%$searchTerm%", "%$searchTerm%"];
} else {
    $paramsTotal = [];
}

$totalStmt = sqlsrv_query($conn, $sqlTotal, $paramsTotal);
$totalRecords = sqlsrv_fetch_array($totalStmt, SQLSRV_FETCH_ASSOC)['total'];
$totalPages = ceil($totalRecords / $limit);

// Generar la paginación
$pagination = '<nav><ul class="pagination justify-content-center">';
for ($i = 1; $i <= $totalPages; $i++) {
    $active = $i === $page ? 'active' : '';
    $pagination .= "<li class='page-item $active'><a class='page-link' href='?page=$i&search=" . urlencode($searchTerm) . "'>$i</a></li>";
}
$pagination .= '</ul></nav>';

// Devolver los resultados y la paginación
echo json_encode([
    'html' => $html,            // Las filas de la tabla
    'pagination' => $pagination // La paginación
]);
?>
