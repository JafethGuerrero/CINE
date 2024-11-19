<?php
include 'conexion.php';

$limit = 10; // Límite de registros por página
$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$start = ($page - 1) * $limit;

$query = isset($_POST['query']) ? $_POST['query'] : '';

// Consulta SQL con búsqueda
$sql = "SELECT id_pelicula, pelicula, fecha_inicio, fecha_limit 
        FROM Cartelera 
        WHERE pelicula LIKE ? 
        ORDER BY pelicula ASC 
        OFFSET ? ROWS FETCH NEXT ? ROWS ONLY";
$params = ["%$query%", $start, $limit];
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Obtener resultados
$peliculas = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $peliculas[] = $row;
}

// Contar total de registros
$countSql = "SELECT COUNT(*) AS total FROM Cartelera WHERE pelicula LIKE ?";
$countStmt = sqlsrv_query($conn, $countSql, ["%$query%"]);
$total = sqlsrv_fetch_array($countStmt, SQLSRV_FETCH_ASSOC)['total'];

$totalPages = ceil($total / $limit);
?>

<!-- Tabla de resultados -->
<table class="table table-bordered">
    <thead class="thead-dark">
        <tr>
            <th>Pelicula</th>
            <th>Fecha Inicio</th>
            <th>Fecha Límite</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($peliculas) > 0): ?>
            <?php foreach ($peliculas as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['pelicula']); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($row['fecha_inicio']->format('Y-m-d'))); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($row['fecha_limit']->format('Y-m-d'))); ?></td>
                    <td>
                        <a href="modificar_pelicula.php?id=<?php echo htmlspecialchars($row['id_pelicula']); ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Modificar
                        </a>
                        <a href="eliminar_pelicula.php?id=<?php echo htmlspecialchars($row['id_pelicula']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar esta película?');">
                            <i class="fas fa-trash-alt"></i> Eliminar
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" class="text-center">No se encontraron resultados</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Paginación -->
<nav>
    <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                <a href="#" class="page-link" data-page="<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>
