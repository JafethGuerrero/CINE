<?php
include 'conexion.php'; // Incluir el archivo de conexión
include 'header.php'; // Incluir el encabezado
include 'footer.php'; // Incluir el footer

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pelicula = $_POST['pelicula'];
    $fecha_limit = $_POST['fecha_limit'];

    // Procesar la imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        // Definir el nombre y la ubicación de la imagen
        $imagen_nombre = $_FILES['imagen']['name'];
        $imagen_tmp = $_FILES['imagen']['tmp_name'];
        $ruta_destino = "imagenes_peliculas/" . $imagen_nombre;

        // Verificar si la carpeta de destino existe, si no, intentar crearla
        if (!is_dir('imagenes_peliculas')) {
            if (!mkdir('imagenes_peliculas', 0777, true)) {
                echo "<script>alert('No se pudo crear la carpeta de imágenes.');</script>";
                exit;
            }
        }

        // Mover la imagen a la carpeta deseada
        if (move_uploaded_file($imagen_tmp, $ruta_destino)) {
            // Si la imagen se subió correctamente, insertar los datos en la base de datos
            $sql = "EXEC InsertarCartelera ?, ?, ?";
            $params = array($pelicula, $fecha_limit, $ruta_destino);
            $stmt = sqlsrv_query($conn, $sql, $params);

            if ($stmt) {
                echo "<script>alert('Película agregada exitosamente.'); window.location.href='cartelera.php';</script>";
            } else {
                echo "<script>alert('Error al agregar la película.');</script>";
            }
        } else {
            echo "<script>alert('Error al subir la imagen. Asegúrate de que la carpeta tenga permisos de escritura.');</script>";
        }
    } else {
        echo "<script>alert('Por favor, seleccione una imagen.');</script>";
    }
}
?>

<div class="container mt-5">
    <h4 class="text-center">Agregar Película</h4>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="pelicula">Nombre de la Película:</label>
            <input type="text" class="form-control" id="pelicula" name="pelicula" required>
        </div>
        <div class="form-group">
            <label for="fecha_limit">Fecha Límite:</label>
            <input type="date" class="form-control" id="fecha_limit" name="fecha_limit" required>
        </div>
        <div class="form-group">
            <label for="imagen">Imagen de la Película:</label>
            <input type="file" class="form-control-file" id="imagen" name="imagen" required>
        </div>
        <button type="submit" class="btn btn-success">Agregar Película</button>
        <a href="cartelera.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
