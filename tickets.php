<?php
include 'conexion.php'; // Incluir archivo de conexión
include 'header.php'; // Incluir encabezado
include 'footer.php'; // Incluir footer

// Verificar si se ha enviado un término de búsqueda
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Modificar la consulta SQL para incluir la búsqueda
$sql = "SELECT B.id_boleto, B.fecha, S.nombre, B.cantidad_asientos, B.numero_asientos
        FROM Boleto B
        JOIN Salas S ON B.id_salas = S.id_salas";
if (!empty($searchTerm)) {
    $sql .= " WHERE B.id_boleto LIKE ? OR S.nombre_sala LIKE ?";
}

// Preparar y ejecutar la consulta
$params = [];
if (!empty($searchTerm)) {
    $params = ["%$searchTerm%", "%$searchTerm%"];
}
$stmt = sqlsrv_query($conn, $sql, $params);
?>

<div class="container mt-5">
    <h2 class="text-center">Listado de Boletos</h2>
    
    <!-- Botón para regresar a la taquilla -->
    <div class="text-center mb-4">
        <a href="taquilla.php" class="btn btn-secondary">Regresar a Taquilla</a>
    </div>
    
    <!-- Formulario de búsqueda -->
    <form method="GET" action="ticket.php" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Buscar por boleto o sala" value="<?php echo htmlspecialchars($searchTerm); ?>">
            <div class="input-group-append">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </div>
    </form>

    <div id="results">
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID Boleto</th>
                    <th>Fecha</th>
                    <th>Sala</th>
                    <th>Cantidad de Asientos</th>
                    <th>Asientos Seleccionados</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id_boleto']); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($row['fecha'])); ?></td>
                        <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($row['cantidad_asientos']); ?></td>
                        <td><?php echo htmlspecialchars($row['numero_asientos']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#search').on('input', function() {
            var searchTerm = $(this).val();
            $.ajax({
                url: 'search_tickets.php',
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
