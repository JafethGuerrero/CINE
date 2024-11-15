<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taquilla</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        /* Mantén el estilo existente */
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Taquilla</h2>

    <form id="search-form" class="mb-4">
        <div class="input-group">
            <input type="text" id="search" name="search" class="form-control" placeholder="Buscar cliente por ID, nombre o celular...">
        </div>
    </form>

    <!-- Resultados de búsqueda -->
    <div id="search-results" class="text-center mb-4"></div>

    <!-- Salas Disponibles -->
    <h4 class="text-center mt-4">Salas Disponibles con Películas</h4>
    <table class="table table-sm">
        <thead>
            <tr>
                <th>Nombre de Sala</th>
                <th>Capacidad</th>
                <th>Película</th>
                <th>Horario</th>
                <th>Seleccionar</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = sqlsrv_fetch_array($stmtSalas, SQLSRV_FETCH_ASSOC)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($row['cantidad_asientos']); ?></td>
                    <td><?php echo htmlspecialchars($row['pelicula'] ?: 'Sin película'); ?></td>
                    <td><?php echo htmlspecialchars($row['horario'] ?: 'Sin horario asignado'); ?></td>
                    <td><button class="btn btn-info select-room" data-id="<?php echo $row['id_salas']; ?>" data-asientos="<?php echo $row['cantidad_asientos']; ?>">Seleccionar</button></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Contenedor para el canvas de selección de asientos -->
<div id="seat-window-overlay"></div>
<div id="seat-window">
    <div class="container-seat">
        <h4>Seleccionar Asientos en Sala</h4>
        <p><strong>Ubicación de la Pantalla: </strong><span id="screen-location"></span></p>
        <div id="seatCanvas"></div>
        <br>
        <button id="close-window" class="btn btn-danger mt-3">Cerrar Ventana</button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Búsqueda en tiempo real
        $('#search').on('input', function() {
            const searchTerm = $(this).val().trim();

            if (searchTerm) {
                $.get('buscar_cliente.php', { search: searchTerm }, function(data) {
                    $('#search-results').html(data);
                });
            } else {
                $('#search-results').empty();
            }
        });

        // Selección de cliente y visualización de asientos
        $(document).on('click', '.select-customer', function() {
            const customerId = $(this).data('id');
            $('#customer-info').html("Cliente seleccionado: " + customerId);
            // Aquí puedes guardar el cliente seleccionado en una variable para usarla con el canvas
        });

        $('.select-room').on('click', function() {
            const roomId = $(this).data('id');
            const seatCount = $(this).data('asientos');
            displaySeats(seatCount, roomId);
        });

        function displaySeats(seatCount, roomId) {
            if (!$('#customer-info').text().includes("Cliente seleccionado")) {
                alert("Debe seleccionar un cliente antes de elegir asientos.");
                return;
            }

            $('#seat-window-overlay').show();
            $('#seat-window').show();
            const seatCanvas = $('#seatCanvas').empty();

            // Generación de asientos para el canvas
            for (let i = 0; i < seatCount; i++) {
                const seatButton = $('<button class="seat available">' +
                    '<span class="material-icons">event_seat</span></button>');
                
                seatCanvas.append(seatButton);
            }

            $('#screen-location').text("Pantalla frente a ustedes");

            $('#close-window').on('click', function() {
                $('#seat-window').hide();
                $('#seat-window-overlay').hide();
            });
        }
    });
</script>
</body>
</html>
