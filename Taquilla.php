<?php
include 'conexion.php'; // Incluir el archivo de conexión
include 'header.php'; // Incluir el encabezado
include 'footer.php'; // Incluir el footer

// Verificar si se ha enviado un término de búsqueda
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$customerData = null; // Inicializar la variable para los datos del cliente

// Lógica para buscar el cliente en la base de datos
if ($searchTerm) {
    $sql = "SELECT * FROM clientes WHERE id_cliente = ? OR nombre LIKE ? OR telefono = ?";
    $params = [$searchTerm, "%" . $searchTerm . "%", $searchTerm];
    $stmt = sqlsrv_query($conn, $sql, $params);
    
    if ($stmt && sqlsrv_has_rows($stmt)) {
        $customerData = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }
}
?>

<div class="container mt-5">
    <h2 class="text-center">Taquilla</h2>
    
    <!-- Formulario de búsqueda de clientes -->
    <form id="search-form" class="mb-4">
        <div class="input-group">
            <input type="text" id="search" name="search" class="form-control" placeholder="Buscar cliente por ID, nombre o teléfono..." value="<?php echo htmlspecialchars($searchTerm); ?>">
            <div class="input-group-append">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </div>
    </form>

    <div id="customer-info" class="text-center mb-4">
        <?php if ($customerData): ?>
            <p><strong>ID:</strong> <?php echo $customerData['id_cliente']; ?></p>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($customerData['nombre']); ?></p>
            <p><strong>Correo Electrónico:</strong> <?php echo htmlspecialchars($customerData['correo_electronico']); ?></p>
            <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($customerData['telefono']); ?></p>
            <button id="buy-ticket" class="btn btn-success">Comprar Boleto</button>
        <?php else: ?>
            <?php if ($searchTerm): ?>
                <p class="text-danger">Cliente no encontrado. Por favor, verifica la información.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Selección de sala -->
    <div id="room-selection" class="text-center d-none">
        <h4>Selecciona una Sala</h4>
        <div id="rooms">
            <label><input type="radio" name="room" value="Sala 1" data-capacity="100"> Sala 1</label>
            <label><input type="radio" name="room" value="Sala 2" data-capacity="80"> Sala 2</label>
            <label><input type="radio" name="room" value="Sala 3" data-capacity="120"> Sala 3</label>
            <!-- Agrega más salas según sea necesario -->
        </div>
    </div>

    <!-- Tabla de Asientos -->
    <div id="seat-selection" class="text-center d-none">
        <h4>Selecciona tu Asiento</h4>
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Asiento</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 1; $i <= 10; $i++): // Cambia el número de asientos según sea necesario ?>
                    <tr>
                        <td>Asiento <?php echo $i; ?></td>
                        <td>
                            <button class="btn btn-secondary select-seat" data-seat="Asiento <?php echo $i; ?>">Seleccionar</button>
                        </td>
                    </tr>
                <?php endfor; ?>
            </tbody>
        </table>
    </div>

    <!-- Tabla de Salas -->
    <h4 class="text-center mt-4">Salas Disponibles</h4>
    <table class="table table-sm">
        <thead>
            <tr>
                <th>Nombre de Sala</th>
                <th>Capacidad</th>
                <th>Tipo de Sala</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Sala 1</td>
                <td>100</td>
                <td>Estándar</td>
            </tr>
            <tr>
                <td>Sala 2</td>
                <td>80</td>
                <td>VIP</td>
            </tr>
            <tr>
                <td>Sala 3</td>
                <td>120</td>
                <td>4D</td>
            </tr>
            <!-- Agrega más salas según sea necesario -->
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        // Manejar la selección de sala
        $('input[name="room"]').on('change', function() {
            // Mostrar la sección de selección de asientos
            $('#seat-selection').removeClass('d-none');
        });

        // Manejar la compra de boletos (simulacro)
        $('#buy-ticket').on('click', function() {
            const selectedRoom = $('input[name="room"]:checked').val();
            const selectedSeat = $('.selected-seat').text();

            if (selectedRoom && selectedSeat) {
                alert(`Boleto comprado exitosamente para el cliente en ${selectedRoom}, asiento ${selectedSeat}.\n(Reporte de cobro generado)`);
                // Opcionalmente, puedes limpiar la información después de comprar
                $('#customer-info').empty();
                $('#buy-ticket').addClass('d-none');
            } else {
                alert('Por favor, selecciona una sala y un asiento.');
            }
        });

        // Manejar la selección de asiento
        $(document).on('click', '.select-seat', function() {
            // Quitar selección anterior
            $('.select-seat').removeClass('btn-primary').addClass('btn-secondary');
            // Seleccionar el nuevo asiento
            $(this).removeClass('btn-secondary').addClass('btn-primary selected-seat');
        });
    });
</script>
</body>
</html>
