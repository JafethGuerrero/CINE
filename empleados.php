<?php
include 'conexion.php'; // Incluir el archivo de conexión
include 'header.php'; // Incluir el encabezado
include 'footer.php'; // Incluir el footer

// Verificar si se ha enviado un término de búsqueda
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Modificar la consulta SQL para incluir la búsqueda
$sql = "SELECT * FROM empleados";
if (!empty($searchTerm)) {
    $sql .= " WHERE nombre LIKE ? OR puesto LIKE ?";
}

// Preparar y ejecutar la consulta
$params = [];
if (!empty($searchTerm)) {
    $params = ["%$searchTerm%", "%$searchTerm%"];
}
$stmt = sqlsrv_query($conn, $sql, $params);
?>

<div class="container mt-5">
    <h2 class="text-center">Lista de Empleados</h2>
    
    <!-- Formulario de búsqueda -->
    <form id="search-form" class="mb-4">
        <div class="input-group">
            <input type="text" id="search" name="search" class="form-control" placeholder="Buscar empleados..." value="<?php echo htmlspecialchars($searchTerm); ?>">
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
    </div>
    <a href="alta_empleado.php" class="btn btn-primary">Agregar Empleado</a>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#search').on('input', function() {
            var searchTerm = $(this).val();
            $.ajax({
                url: 'search_empleados.php',
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
