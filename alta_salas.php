<?php
session_start();
include 'header.php';
include 'footer.php';

// Notificación de estado
if (isset($_SESSION['status'])) {
    $statusMessage = $_SESSION['status']['message'];
    $statusType = $_SESSION['status']['type'];
    unset($_SESSION['status']);
}

// Incluir la conexión a la base de datos
include 'conexion.php';

// Consulta para obtener las películas (reemplaza 'titulo' con el nombre correcto de la columna)
$sqlPeliculas = "SELECT id_pelicula, pelicula FROM cartelera"; // Cambia 'titulo' según sea necesario
$resultPeliculas = sqlsrv_query($conn, $sqlPeliculas);

if ($resultPeliculas === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta de Salas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 20px;
            background-color: #f8f9fa;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px; /* Limitando el ancho del formulario */
            margin: 0 auto; /* Centrar el contenedor */
        }
        .notification {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Notificación -->
    <?php if (isset($statusMessage)): ?>
        <div class="notification alert alert-<?php echo $statusType; ?>" role="alert">
            <strong><?php echo ucfirst($statusType); ?>:</strong> <?php echo $statusMessage; ?>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const notification = document.querySelector('.notification');
                setTimeout(() => {
                    notification.style.display = 'none';
                }, 5000);
            });
        </script>
    <?php endif; ?>

    <!-- Contenido Principal -->
    <div class="container mt-5">
        <h2 class="text-center">Alta de Salas</h2>
        <div class="form-container">
            <h4>Agregar Sala</h4>
            <form action="alta_sala_logic.php" method="POST">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre de la Sala (*)</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="mb-3">
                    <label for="cantidad_asientos" class="form-label">Cantidad de Asientos (*)</label>
                    <input type="number" class="form-control" id="cantidad_asientos" name="cantidad_asientos" required>
                </div>
                <div class="mb-3">
                    <label for="tipo_proyeccion" class="form-label">Tipo de Proyección (*)</label>
                    <select class="form-control" id="tipo_proyeccion" name="tipo_proyeccion" required>
                            <option value="" disabled selected>Seleccione un tipo de proyección</option>
                            <option value="2D">2D</option>
                            <option value="3D">3D</option>
                            <option value="IMAX">IMAX</option>
                            <option value="4DX">4DX</option>
                            <option value="D-BOX">D-BOX</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Agregar Sala</button>
                <a href="salas.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
