<?php
include 'conexion.php'; // Incluir el archivo de conexión
include 'headeralm.php'; // Incluir el encabezado
include 'footer.php'; // Incluir el footer

// Verificar si se ha enviado un término de búsqueda
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Modificar la consulta SQL para incluir la búsqueda con JOIN para obtener los nombres
$sql = "
    SELECT A.id_almacen, P.nombre_producto, PR.nombre_proveedor, 
           A.cantidad, A.tipo_almacenamiento, A.fecha_reabastecimiento
    FROM Almacen A
    JOIN Productos P ON A.id_producto = P.id_producto
    JOIN Proveedor PR ON A.id_proveedor = PR.id_proveedor
";

// Preparar y ejecutar la consulta
$params = [];
if (!empty($searchTerm)) {
    $sql .= " WHERE A.tipo_almacenamiento LIKE ? 
              OR P.nombre_producto LIKE ? 
              OR PR.nombre_proveedor LIKE ?";
    $params = ["%$searchTerm%", "%$searchTerm%", "%$searchTerm%"];
}

// Preparar la consulta
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    // Muestra los errores detallados de SQL Server
    die(print_r(sqlsrv_errors(), true));
}
?>

<div class="container mt-5">
    <h2 class="text-center">Lista de Almacén</h2>
    
    <div class="mb-3 text-center">
        <a href="proveedores.php" class="btn btn-info btn-animate">Ver Proveedores</a>
        <a href="productos.php" class="btn btn-warning btn-animate">Ver Productos</a>
    </div>

    <!-- Formulario de búsqueda -->
    <form id="search-form" class="mb-4">
        <div class="input-group">
            <input type="text" id="search" name="search" class="form-control" placeholder="Buscar en almacén..." value="<?php echo htmlspecialchars($searchTerm); ?>">
            <div class="input-group-append">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </div>
    </form>

    <div id="results">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre Producto</th>
                    <th>Nombre Proveedor</th>
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
                        <td><?php echo htmlspecialchars($row['cantidad']); ?></td>
                        <td><?php echo htmlspecialchars($row['tipo_almacenamiento']); ?></td>
                        <td><?php echo htmlspecialchars($row['fecha_reabastecimiento']); ?></td>
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
    </div>
    <a href="alta_almacen.php" class="btn btn-primary">Agregar Almacén</a>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#search').on('input', function() {
            var searchTerm = $(this).val();
            $.ajax({
                url: 'search_almacen.php',
                method: 'GET',
                data: { search: searchTerm },
                success: function(data) {
                    $('#results').html(data);
                }
            });
        });
    });
</script>
</body>
</html>
