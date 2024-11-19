<?php
include 'conexion.php';

// Obtener el término de búsqueda y la página actual desde los parámetros de la URL
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Consulta para obtener los datos con los filtros aplicados
$sql = "
    SELECT A.id_almacen, P.nombre_producto, PR.nombre_proveedor, 
           A.cantidad, A.tipo_almacenamiento, A.fecha_reabastecimiento
    FROM Almacen A
    JOIN Productos P ON A.id_producto = P.id_producto
    JOIN Proveedor PR ON A.id_proveedor = PR.id_proveedor
";

$params = [];
if (!empty($searchTerm)) {
    $sql .= " WHERE A.tipo_almacenamiento LIKE ? 
              OR P.nombre_producto LIKE ? 
              OR PR.nombre_proveedor LIKE ?";
    $params = ["%$searchTerm%", "%$searchTerm%", "%$searchTerm%"];
}

$sql .= " ORDER BY A.id_almacen OFFSET ? ROWS FETCH NEXT ? ROWS ONLY";
$params[] = $offset;
$params[] = $limit;

$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

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
    $sqlTotal .= " WHERE A.tipo_almacenamiento LIKE ? 
                   OR P.nombre_producto LIKE ? 
                   OR PR.nombre_proveedor LIKE ?";
    $paramsTotal = ["%$searchTerm%", "%$searchTerm%", "%$searchTerm%"];
} else {
    $paramsTotal = [];
}

$totalStmt = sqlsrv_query($conn, $sqlTotal, $paramsTotal);
$totalRecords = sqlsrv_fetch_array($totalStmt, SQLSRV_FETCH_ASSOC)['total'];
$totalPages = ceil($totalRecords / $limit);

// Generar la paginación
$pagination = '<nav><ul class="pagination justify-content-center">';
for ($i = 1; $i <= $totalPages; $i++) {
    $pagination .= "<li class='page-item'><a class='page-link' href='almacen.php?page=$i&search=" . urlencode($searchTerm) . "'>$i</a></li>";
}
$pagination .= '</ul></nav>';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Almacén</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body{
            background-color: #f8f9fa;
        }
        .search-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-container input {
            flex-grow: 1;
            margin-right: 10px;
        }

        .add-button:hover {
            background-color: #218838;
        }

        .action-buttons .btn {
            margin-right: 5px;
        }

        .btn-hover:hover {
            background-color: #f0ad4e;
        }

        .action-buttons a {
            text-decoration: none;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?> <!-- Incluye el header -->

<div class="container mt-5">
    <!-- Título de la página centrado -->
    <h2 class="text-center"> Lista de Almacén</h1>
    <div class="mt-3 text-center">
        <a href="productos.php" class="btn btn-info btn-animate">
            <i class="fa fa-box-open"></i> Ver Productos
        </a>
        <a href="proveedores.php" class="btn btn-warning btn-animate">
            <i class="fa fa-truck"></i> Ver Proveedores
        </a>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
    <!-- Barra de búsqueda y botón de agregar -->
    <form id="search-form" class="d-flex">
        <input type="text" id="search" name="search" class="form-control me-2" placeholder="Buscar..." value="<?php echo htmlspecialchars($searchTerm); ?>">
        <button type="submit" class="btn btn-primary me-2" disabled>Buscar</button>
    </form>
    
    <!-- Botón de agregar -->
    <a href="alta_proveedor.php" class="btn btn-success" title="Agregar Proveedor">
                <i class="fa fa-plus"></i>
    </a>
    </div>

    <!-- Tabla de resultados -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Almacén</th>
                <th>Producto</th>
                <th>Proveedor</th>
                <th>Cantidad</th>
                <th>Tipo de Almacenamiento</th>
                <th>Fecha de Reabastecimiento</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="results">
            <?php echo $html; ?>
        </tbody>
    </table>

    <!-- Paginación -->
    <div id="pagination">
        <?php echo $pagination; ?>
    </div>
</div>

<?php include 'footer.php'; ?> <!-- Incluye el footer -->

<script>
$(document).ready(function () {
    // Función para cargar los datos
    function loadData(page , searchTerm = '') {
        $.ajax({
            url: 'almacen.php', // Llamar a la misma página
            method: 'GET',
            data: { search: searchTerm, page: page },
            success: function (response) {
                const data = $(response);
                $('#results').html(data.find('#results').html());  // Actualizar los resultados
                $('#pagination').html(data.find('#pagination').html());  // Actualizar la paginación
            }
        });
    }

    // Realizar búsqueda cuando el usuario escribe
    $('#search').on('input', function() {
        const searchTerm = $(this).val();
        loadData(1, searchTerm);  // Cargar la primera página con el término de búsqueda
    });

    // Cargar una página específica cuando el usuario hace clic en la paginación
    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        const page = $(this).attr('href').split('=')[1];
        loadData(page, $('#search').val());  // Cargar los datos para la página seleccionada
    });

    // Cargar los datos iniciales
    loadData();
});
</script>
</body>
</html>
