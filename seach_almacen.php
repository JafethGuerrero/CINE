<?php
include 'conexion.php';

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';  // Obtener el término de búsqueda
$page = isset($_GET['page']) ? $_GET['page'] : 1;  // Obtener la página actual (por defecto 1)
$limit = 10;  // Número de registros por página
$offset = ($page - 1) * $limit;  // Calcular el desplazamiento para la paginación

// Consulta SQL para obtener los datos de la tabla Almacen con JOIN a Productos y Proveedor
$sql = "
    SELECT A.id_almacen, P.nombre_producto, PR.nombre_proveedor, 
           A.cantidad, A.tipo_almacenamiento, A.fecha_reabastecimiento
    FROM Almacen A
    JOIN Productos P ON A.id_producto = P.id_producto
    JOIN Proveedor PR ON A.id_proveedor = PR.id_proveedor
";

$params = [];
if (!empty($searchTerm)) {
    // Si hay un término de búsqueda, filtrar por ese término
    $sql .= " WHERE A.tipo_almacenamiento LIKE ? 
              OR P.nombre_producto LIKE ? 
              OR PR.nombre_proveedor LIKE ?";
    $params = ["%$searchTerm%", "%$searchTerm%", "%$searchTerm%"];
}

$sql .= " ORDER BY A.id_almacen OFFSET ? ROWS FETCH NEXT ? ROWS ONLY";
$params[] = $offset;
$params[] = $limit;

// Ejecutar la consulta con parámetros
$stmt = sqlsrv_query($conn, $sql, $params);

// Verificar si hay errores en la consulta
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Generar las filas de la tabla para mostrar los resultados
$html = '';
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $html .= "<tr>
                <td>{$row['id_almacen']}</td>
                <td>{$row['nombre_producto']}</td>
                <td>{$row['nombre_proveedor']}</td>
                <td>{$row['cantidad']}</td>
                <td>{$row['tipo_almacenamiento']}</td>
                <td>{$row['fecha_reabastecimiento']}</td>
                <td class='text-center'>
                    <a href='edit_almacen.php?id={$row['id_almacen']}' class='btn btn-default' title='Modificar'>
                        <i class='fa fa-pencil'></i>
                    </a>
                    <a href='eliminar_almacen.php?id={$row['id_almacen']}' class='btn btn-default' title='Eliminar'>
                        <i class='fa fa-remove'></i>
                    </a>
                </td>
              </tr>";
}

// Obtener el total de registros para la paginación
$sqlTotal = "
    SELECT COUNT(*) AS total
    FROM Almacen A
    JOIN Productos P ON A.id_producto = P.id_producto
    JOIN Proveedor PR ON A.id_proveedor = PR.id_proveedor
";
if (!empty($searchTerm)) {
    // Si hay término de búsqueda, también agregar las condiciones al total
    $sqlTotal .= " WHERE A.tipo_almacenamiento LIKE ? 
                   OR P.nombre_producto LIKE ? 
                   OR PR.nombre_proveedor LIKE ?";
    $paramsTotal = ["%$searchTerm%", "%$searchTerm%", "%$searchTerm%"];
} else {
    $paramsTotal = [];
}

$totalStmt = sqlsrv_query($conn, $sqlTotal, $paramsTotal);
$totalRecords = sqlsrv_fetch_array($totalStmt, SQLSRV_FETCH_ASSOC)['total'];
$totalPages = ceil($totalRecords / $limit);  // Calcular el número total de páginas

// Generar la paginación
$pagination = '<nav><ul class="pagination justify-content-center">';
for ($i = 1; $i <= $totalPages; $i++) {
    $pagination .= "<li class='page-item'><a class='page-link' href='almacen.php?page=$i&search=" . urlencode($searchTerm) . "'>$i</a></li>";
}
$pagination .= '</ul></nav>';

// Devolver los resultados en formato HTML
echo json_encode([
    'html' => $html,            // Las filas de la tabla
    'pagination' => $pagination // La paginación
]);
?>
