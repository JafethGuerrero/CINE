<?php
include 'conexion.php';
include 'footer.php';

// Obtener el término de búsqueda y la página actual desde los parámetros de la URL
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10; // Número de registros por página
$offset = ($page - 1) * $limit;

// Consulta para obtener los datos con los filtros aplicados
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
              </tr>";
}

// Consulta para obtener el total de registros
$sqlTotal = "
    SELECT COUNT(*) AS total
    FROM clientes";

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
    $pagination .= "<li class='page-item $active'><a class='page-link' href='clientes.php?page=$i&search=" . urlencode($searchTerm) . "'>$i</a></li>";
}
$pagination .= '</ul></nav>';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Clientes</title>
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
        .table-container {
            background-color: #ffffff;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?> <!-- Incluye el header -->
<div class="container mt-5">
    <h2 class="text-center">Lista de Clientes</h2>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
    <!-- Barra de búsqueda -->
    <form id="search-form" class="d-flex">
        <div class="input-group">
            <input type="text" id="search" name="search" class="form-control" placeholder="Buscar clientes..." value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button type="submit" class="btn btn-primary" disabled>Buscar</button>
        </div>
    </form>

    <!-- Boton agregar -->
    <a href="alta_cliente.php" class="btn btn-success" title="Agregar Cliente">
        <i class="fa fa-plus"></i>
    </a>
    </div>

    <!-- Tabla de resultados -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Cliente</th>
                <th>Nombre</th>
                <th>Correo Electrónico</th>
                <th>Celular</th>
                <th>Cuenta Bancaria</th>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    function loadData(page, searchTerm = '') {
        $.ajax({
            url: 'clientes.php', // Llamar a la misma página
            method: 'GET',
            data: { page: page, search: searchTerm },
            success: function (response) {
                const data = $(response);
                $('#results').html(data.find('#results').html());  // Actualizar los resultados
                $('#pagination').html(data.find('#pagination').html());  // Actualizar la paginación
            }
        });
    }
    // Realizar búsqueda cuando el usuario escribe
    $('#search').on('input', function () {
        const searchTerm = $(this).val();
        loadData(1, searchTerm);  // Cargar la primera página con el término de búsqueda
    });

    // Cargar una página específica cuando el usuario hace clic en la paginación
    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        const url = $(this).attr('href').split('=')[1];
        loadData(page,$('#search').val());  // Cargar los datos para la página seleccionada
    });

    // Cargar los datos iniciales
    loadData();
});
</script>
</body>
</html>
