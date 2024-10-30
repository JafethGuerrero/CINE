<?php
include 'conexion.php'; // Incluir el archivo de conexión
include 'header.php'; // Incluir el encabezado
include 'footer.php'; // Incluir el footer

// Verificar si se ha enviado un término de búsqueda
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Modificar la consulta SQL para incluir la búsqueda
$sql = "SELECT * FROM Almacen";
if (!empty($searchTerm)) {
    $sql .= " WHERE tipo_almacenamiento LIKE ? OR ubicacion_producto LIKE ?";
}

// Preparar y ejecutar la consulta
$params = [];
if (!empty($searchTerm)) {
    $params = ["%$searchTerm%", "%$searchTerm%"];
}
$stmt = sqlsrv_query($conn, $sql, $params);
?>

<div class="container mt-5">
    <h2 class="text-center">Lista de Almacen</h2>
    
    <!-- Formulario de búsqueda -->
    <form id="search-form" class="mb-4">
        <div class="input-group">
            <input type="text" id="search" name="search" class="form-control" placeholder="Buscar productos..." value="<?php echo htmlspecialchars($searchTerm); ?>">
            <div class="input-group-append">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </div>
    </form>

    <div id="results">
        <table class="table">
            <thead>
                <tr>
                    <th>ID Almacen</th>
                    <th>ID Producto</th>
                    <th>ID Proveedor</th>
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
                        <td><?php echo htmlspecialchars($row['id_producto']); ?></td>
                        <td><?php echo htmlspecialchars($row['id_proveedor']); ?></td>
                        <td><?php echo htmlspecialchars($row['ubicacion_producto']); ?></td>
                        <td><?php echo htmlspecialchars($row['cantidad']); ?></td>
                        <td><?php echo htmlspecialchars($row['tipo_almacenamiento']); ?></td>
                        <td><?php echo htmlspecialchars($row['fecha_reabastecimiento'] ? $row['fecha_reabastecimiento']->format('Y-m-d') : 'N/A'); ?></td>
                        <td class="text-center">
                            <a href="./edit_almacen.php?id=<?php echo $row['id_almacen']; ?>" class="btn btn-default" title="Modificar">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <a href="./eliminar_almacen.php?id=<?php echo $row['id_almacen']; ?>" class="btn btn-default" title="Eliminar">
                                <i class="fa fa-remove"></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <a href="alta_almacen.php" class="btn btn-primary">Agregar Almacen</a>
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
