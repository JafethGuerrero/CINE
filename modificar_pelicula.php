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
    $sql = "SELECT id_pelicula, pelicula, fecha_limit, imagen FROM Cartelera WHERE id_pelicula = ?";
    $params = array($id_pelicula);
    $stmt = sqlsrv_query($conn, $sql, $params);

    // Verificar si la consulta se ejecutó correctamente
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true)); // Imprimir errores de la consulta si existen
    }
    
    // Verificamos si se encontró la película
    $pelicula = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    // Si no se encontró la película, redirigir con mensaje
    if (!$pelicula) {
        echo "<script>alert('Película no encontrada.'); window.location.href='cartelera.php';</script>";
        exit();  // Detenemos el script para evitar seguir ejecutando
    }
} else {
    // Si no se proporciona el ID de la película, redirigimos a la cartelera
    echo "<script>alert('ID de película no proporcionado.'); window.location.href='cartelera.php';</script>";
    exit();
}

// Verificar si el formulario fue enviado para actualizar la película
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pelicula_name = $_POST['pelicula'];
    $fecha_limit = $_POST['fecha_limit'];

    // Validar que los datos no estén vacíos
    if (!empty($pelicula_name) && !empty($fecha_limit)) {
        // Verificamos si se cargó un archivo de imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
            $imagen = $_FILES['imagen'];
            $imagen_nombre = time() . '-' . basename($imagen['name']); // Nombre único para la imagen
            $imagen_path = 'imagenes/' . $imagen_nombre; // Ruta de destino

            // Mover el archivo cargado al directorio de imágenes
            if (move_uploaded_file($imagen['tmp_name'], $imagen_path)) {
                // Actualizar la película en la base de datos, incluyendo la imagen
                $sql = "EXEC ActualizarCartelera ?, ?, ?, ?";
                $params = array($id_pelicula, $pelicula_name, $fecha_limit, $imagen_path);
                $stmt = sqlsrv_query($conn, $sql, $params);

                if ($stmt === false) {
                    die(print_r(sqlsrv_errors(), true)); // Imprimir errores si la consulta falla
                }

                echo "<script>alert('Película modificada exitosamente.'); window.location.href='cartelera.php';</script>";
                exit();
            } else {
                echo "<script>alert('Error al cargar la imagen.');</script>";
            }
        } else {
            // Si no se carga nueva imagen, solo actualizar los otros campos
            $sql = "EXEC ActualizarCarteleraSinImagen ?, ?, ?";
            $params = array($id_pelicula, $pelicula_name, $fecha_limit);
            $stmt = sqlsrv_query($conn, $sql, $params);

            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true)); // Imprimir errores si la consulta falla
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

    <!-- Verificamos si $pelicula tiene datos antes de mostrar el formulario -->
    <?php if ($pelicula): ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="pelicula">Nombre de la Película:</label>
            <input type="text" class="form-control" id="pelicula" name="pelicula" value="<?php echo htmlspecialchars($pelicula['pelicula']); ?>" required>
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
        <button type="submit" class="btn btn-warning">Actualizar Película</button>
        <a href="cartelera.php" class="btn btn-secondary">Cancelar</a>
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
