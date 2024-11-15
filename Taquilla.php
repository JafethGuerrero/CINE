<?php
session_start();
include 'conexion.php';
include 'header.php';
include 'footer.php';

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$customerData = null;

if ($searchTerm) {
    $sql = "SELECT * FROM clientes WHERE id_cliente = ? OR nombre LIKE ? OR celular = ?";
    $params = [$searchTerm, "%" . $searchTerm . "%", $searchTerm];
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    if (sqlsrv_has_rows($stmt)) {
        $customerData = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }
}

$sqlSalas = "SELECT salas.id_salas, salas.nombre, salas.cantidad_asientos, 
             salas.pelicula, cartelera.horario
             FROM salas 
             LEFT JOIN cartelera ON salas.id_pelicula = cartelera.id_pelicula";  
$stmtSalas = sqlsrv_query($conn, $sqlSalas);

if ($stmtSalas === false) {
    die(print_r(sqlsrv_errors(), true));  
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taquilla</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        /* Estilos para el contenedor del canvas */
        #seat-window {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 80%;
            max-height: 80%;
            overflow: auto;
        }

        #seatCanvas {
            display: grid;
            grid-template-columns: repeat(10, 50px); /* 10 columnas */
            gap: 10px;
            justify-content: center;
        }

        .seat {
            width: 50px;
            height: 50px;
            background-color: green;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            cursor: pointer;
            border-radius: 5px;
            font-size: 30px;
        }

        .seat.occupied {
            background-color: gray;
            cursor: not-allowed;
        }

        .seat.reserved {
            background-color: orange;
        }

        .seat.available {
            background-color: green;
        }

        .seat.selected {
            background-color: blue;
        }

        .seat-label {
            font-size: 12px;
            color: #333;
            margin-top: 5px;
        }

        .container-seat {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
        }

        .select-room {
            cursor: pointer;
        }

        #close-window {
            position: absolute;
            bottom: 10px;
            right: 10px;
        }

        #seat-window-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Taquilla</h2>

    <form id="search-form" class="mb-4" method="GET">
        <div class="input-group">
            <input type="text" id="search" name="search" class="form-control" placeholder="Buscar cliente por ID, nombre o celular..." value="<?php echo htmlspecialchars($searchTerm); ?>">
            <div class="input-group-append">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </div>
    </form>

    <div id="customer-info" class="text-center mb-4">
        <?php if ($customerData): ?>
            <p><strong>ID:</strong> <?php echo htmlspecialchars($customerData['id_cliente']); ?></p>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($customerData['nombre']); ?></p>
            <p><strong>Correo Electrónico:</strong> <?php echo htmlspecialchars($customerData['correo_electronico']); ?></p>
            <p><strong>Celular:</strong> <?php echo htmlspecialchars($customerData['celular']); ?></p>
            <button id="buy-ticket" class="btn btn-success">Comprar Boleto</button>
        <?php else: ?>
            <?php if ($searchTerm): ?>
                <p class="text-danger">Cliente no encontrado. Por favor, verifica la información.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>

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

<div id="seat-window-overlay"></div>
<div id="seat-window">
    <div class="container-seat">
        <h4>Seleccionar Asientos en Sala</h4>
        <p><strong>Ubicación de la Pantalla: </strong><span id="screen-location"></span></p>
        <div id="seatCanvas"></div>
        <button id="close-window" class="btn btn-danger mt-3">Cerrar Ventana</button>
    </div>
</div>

<!-- Agregamos botones para redirigir a otras páginas -->
<div class="text-center mt-4">
    <a href="tickets.php" class="btn btn-primary">Ver Boletos</a>
    <a href="cartelera.php" class="btn btn-secondary">Ver Cartelera</a>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        let selectedSeats = [];

        $('.select-room').on('click', function() {
            const roomId = $(this).data('id');
            const seatCount = $(this).data('asientos');
            displaySeats(seatCount, roomId);
        });

        function displaySeats(seatCount, roomId) {
            if (!<?php echo json_encode($customerData); ?>) {
                alert("Debe ingresar un cliente antes de seleccionar un asiento.");
                return;
            }

            $('#seat-window-overlay').show();
            $('#seat-window').show();
            const seatCanvas = $('#seatCanvas').empty();

            for (let i = 0; i < seatCount; i++) {
                const seatButton = $('<button class="seat available"><span class="material-icons">event_seat</span></button>');

                seatButton.on('click', function() {
                    if (!$(this).hasClass('occupied')) {
                        if ($(this).hasClass('selected')) {
                            $(this).removeClass('selected').addClass('available').css('background-color', 'green');
                            selectedSeats = selectedSeats.filter(seat => seat !== i);
                        } else {
                            $(this).addClass('selected').css('background-color', 'blue');
                            selectedSeats.push(i);
                        }
                    }
                });

                seatCanvas.append(seatButton);
            }
        }

        $('#close-window').on('click', function() {
            $('#seat-window-overlay').hide();
            $('#seat-window').hide();
        });

        $('#buy-ticket').on('click', function() {
            if (selectedSeats.length === 0) {
                alert("Por favor, seleccione al menos un asiento.");
                return;
            }

            const customerId = <?php echo json_encode($customerData['id_cliente']); ?>;
            const selectedSeatsString = selectedSeats.join(',');

            $.post('comprar_boleto.php', { customerId, selectedSeats: selectedSeatsString }, function(response) {
                alert(response.message);
                window.location.href = 'tickets.php';
            });
        });
    });
</script>

</body>
</html>
