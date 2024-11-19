<?php
session_start();
include 'conexion.php';
include 'header.php';
include 'footer.php';

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Obtener las salas disponibles
$sqlSalas = "SELECT id_salas, nombre, cantidad_asientos FROM salas";
$stmtSalas = sqlsrv_query($conn, $sqlSalas);

if ($stmtSalas === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Obtener las películas disponibles
$sqlPeliculas = "SELECT id_pelicula, pelicula FROM cartelera"; // Cambié 'titulo' por 'pelicula'
$stmtPeliculas = sqlsrv_query($conn, $sqlPeliculas);

if ($stmtPeliculas === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Preparar las películas en un array
$peliculas = [];
while ($row = sqlsrv_fetch_array($stmtPeliculas, SQLSRV_FETCH_ASSOC)) {
    $peliculas[] = $row;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Horarios y Películas</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Asignar Horarios y Películas a Salas</h2>

    <div class="d-flex justify-content align-items-center">
        <a href="taquilla.php" class="btn btn-primary">Volver</a>
    </div>
    <!-- Formulario para asignar horarios y películas -->
    <form id="assign-form">
        <table class="table table-bordered mt-4">
            <thead class="thead-dark">
                <tr>
                    <th>Nombre de Sala</th>
                    <th>Capacidad</th>
                    <th>Película</th>
                    <th>Horario</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($sala = sqlsrv_fetch_array($stmtSalas, SQLSRV_FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($sala['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($sala['cantidad_asientos']); ?></td>
                        <td>
                            <select class="form-control pelicula-select" data-sala-id="<?php echo $sala['id_salas']; ?>">
                                <option value="">Seleccionar película</option>
                                <?php foreach ($peliculas as $pelicula): ?>
                                    <option value="<?php echo $pelicula['id_pelicula']; ?>">
                                        <?php echo htmlspecialchars($pelicula['pelicula']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-control horario-input" placeholder="Horario (ej. 15:30)" data-sala-id="<?php echo $sala['id_salas']; ?>">
                        </td>
                        <td>
                            <button type="button" class="btn btn-success assign-btn" data-sala-id="<?php echo $sala['id_salas']; ?>">Asignar</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.assign-btn').on('click', function() {
            const salaId = $(this).data('sala-id');
            const peliculaId = $(`.pelicula-select[data-sala-id="${salaId}"]`).val();
            const horario = $(`.horario-input[data-sala-id="${salaId}"]`).val();

            // Validación de formato de horario (HH:mm-HH:mm, HH:mm-HH:mm, ...)
            const horarioPattern = /^([01]?[0-9]|2[0-3]):([0-5][0-9])-([01]?[0-9]|2[0-3]):([0-5][0-9])(?:,([01]?[0-9]|2[0-3]):([0-5][0-9])-([01]?[0-9]|2[0-3]):([0-5][0-9]))*$/;

            if (!peliculaId || !horario) {
                alert('Por favor, seleccione una película y un horario.');
                return;
            }

            if (!horario.match(horarioPattern)) {
                alert('Por favor, ingrese un horario válido en formato HH:mm-HH:mm (por ejemplo, 8:10-10:45, 11:00-1:15).');
                return;
            }

            // Llamar al stored procedure para guardar la asignación
            $.post('guardar_asignacion.php', { salaId, peliculaId, horario }, function(response) {
                alert(response.message || 'Asignación guardada exitosamente.');
                window.location.href = 'taquilla.php';  // Redirige a taquilla.php después de guardar
            }).fail(function() {
                alert('Error al guardar la asignación.');
            });
        });
    });
</script>
</body>
</html>
