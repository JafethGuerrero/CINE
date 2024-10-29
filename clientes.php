<?php
include 'conexion.php'; // Incluir el archivo de conexión
include 'header.php'; // Incluir el encabezado
include 'footer.php'; // Incluir el footer

// Verificar si se ha enviado un término de búsqueda
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Modificar la consulta SQL para incluir la búsqueda
$sql = "SELECT * FROM clientes";
if (!empty($searchTerm)) {
    $sql .= " WHERE nombre LIKE ? OR correo_electronico LIKE ? OR celular LIKE ? OR cuenta_bancaria LIKE ?";
}

// Preparar y ejecutar la consulta
$params = [];
if (!empty($searchTerm)) {
    $params = ["%$searchTerm%", "%$searchTerm%", "%$searchTerm%", "%$searchTerm%"];
}
$stmt = sqlsrv_query($conn, $sql, $params);
?>

<div class="container mt-5">
    <h2 class="text-center">Lista de Clientes</h2>
    
    <!-- Formulario de búsqueda -->
    <form id="search-form" class="mb-4">
        <div class="input-group">
            <input type="text" id="search" name="search" class="form-control" placeholder="Buscar clientes..." value="<?php echo htmlspecialchars($searchTerm); ?>">
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
                    <th>Correo Electrónico</th>
                    <th>Celular</th>
                    <th>Cuenta Bancaria</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id_cliente']); ?></td>
                        <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($row['correo_electronico']); ?></td>
                        <td><?php echo htmlspecialchars($row['celular']); ?></td>
                        <td><?php echo htmlspecialchars($row['cuenta_bancaria']); ?></td>
                        <td class="text-center">
                            <a href="./edit_cliente.php?id=<?php echo $row['id_cliente']; ?>" class="btn btn-default" title="Modificar">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <a href="./eliminar_cliente.php?id=<?php echo $row['id_cliente']; ?>" class="btn btn-default" title="Eliminar">
                                <i class="fa fa-remove"></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <a href="alta_cliente.php" class="btn btn-primary">Agregar Cliente</a>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#search').on('input', function() {
            var searchTerm = $(this).val();
            $.ajax({
                url: 'search_clientes.php',
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
