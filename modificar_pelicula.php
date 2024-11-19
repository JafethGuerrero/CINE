<?php
include 'conexion.php'; // Incluir el archivo de conexión
include 'header.php'; // Incluir el encabezado
include 'footer.php'; // Incluir el footer

// Inicializamos la variable $pelicula
$pelicula = null;

// Verificar si el ID de la película está en la URL
if (isset($_GET['id'])) {
    $id_pelicula = $_GET['id'];

    // Obtener los detalles de la película desde la base de datos
    $sql = "SELECT id_pelicula, pelicula, fecha_inicio, fecha_limit, imagen FROM Cartelera WHERE id_pelicula = ?";
    $params = array($id_pelicula);
    $stmt = sqlsrv_query($conn, $sql, $params);

    // Verificar si la consulta se ejecutó correctamente
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    
    // Verificamos si se encontró la película
    $pelicula = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    if (!$pelicula) {
        echo "<script>alert('Película no encontrada.'); window.location.href='cartelera.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('ID de película no proporcionado.'); window.location.href='cartelera.php';</script>";
    exit();
}

// Verificar si el formulario fue enviado para actualizar la película
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pelicula_name = $_POST['pelicula'];
    $fecha_limit = $_POST['fecha_limit'];

    // Validar campos obligatorios
    if (!empty($pelicula_name) && !empty($fecha_limit)) {
        // Validar formato de la fecha
        if (DateTime::createFromFormat('Y-m-d', $fecha_limit) === false) {
            echo "<script>alert('Fecha límite no válida.');</script>";
            exit();
        }

        // Procesar imagen, si se sube
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
            $imagen = $_FILES['imagen'];
            $imagen_nombre = time() . '-' . basename($imagen['name']);
            $directorio_imagenes = 'imagenes_peliculas/';

            if (!file_exists($directorio_imagenes)) {
                if (!mkdir($directorio_imagenes, 0777, true)) {
                    die('Error: No se pudo crear el directorio de imágenes.');
                }
            }

            $imagen_path = $directorio_imagenes . $imagen_nombre;

            if (move_uploaded_file($imagen['tmp_name'], $imagen_path)) {
                // Llamar al procedimiento almacenado con imagen
                $sql = "EXEC ActualizarCartelera ?, ?, ?, ?";
                $params = array($id_pelicula, $pelicula_name, $fecha_limit, $imagen_path);
                $stmt = sqlsrv_query($conn, $sql, $params);

                if ($stmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }

                echo "<script>alert('Película modificada exitosamente.'); window.location.href='cartelera.php';</script>";
                exit();
            } else {
                echo "<script>alert('Error al cargar la imagen.');</script>";
            }
        } else {
            // Llamar al procedimiento almacenado sin imagen
            $sql = "EXEC ActualizarCarteleraSinImagen ?, ?, ?";
            $params = array($id_pelicula, $pelicula_name, $fecha_limit);
            $stmt = sqlsrv_query($conn, $sql, $params);

            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }

            echo "<script>alert('Película modificada exitosamente.'); window.location.href='cartelera.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Por favor, complete todos los campos.');</script>";
    }
}
?>

<div class="container mt-5">
    <h4 class="text-center">Modificar Película</h4>

    <?php if ($pelicula): ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="pelicula">Nombre de la Película:</label>
            <input type="text" class="form-control" id="pelicula" name="pelicula" value="<?php echo htmlspecialchars($pelicula['pelicula']); ?>" required>
        </div>
        <div class="form-group">
            <label for="fecha_inicio">Fecha Inicio:</label>
            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo $pelicula['fecha_inicio'] ? $pelicula['fecha_inicio']->format('Y-m-d') : ''; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="fecha_limit">Fecha Límite:</label>
            <input type="date" class="form-control" id="fecha_limit" name="fecha_limit" value="<?php echo $pelicula['fecha_limit'] ? $pelicula['fecha_limit']->format('Y-m-d') : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="imagen">Imagen de la Película:</label>
            <input type="file" class="form-control-file" id="imagen" name="imagen">
            <?php if (!empty($pelicula['imagen'])): ?>
                <p>Imagen actual: <a href="<?php echo htmlspecialchars($pelicula['imagen']); ?>" target="_blank">Ver imagen</a></p>
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-success">Actualizar Película</button>
        <a href="cartelera.php" class="btn btn-info">Cancelar</a>
    </form>
    <?php else: ?>
        <p class="text-danger">No se pudo cargar la información de la película. Intenta nuevamente.</p>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
