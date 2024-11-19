<?php
include 'header.php'; // Incluir el header
include 'conexion.php'; // Incluir la conexión a la base de datos

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pelicula = $_POST['pelicula']; // Nombre de la película
    $fecha_inicio = $_POST['fecha_inicio']; // Fecha de inicio
    $fecha_limit = $_POST['fecha_limit']; // Fecha límite de la película

    // Preparar el SQL para ejecutar el procedimiento agregar_pelicula
    $sql = "EXEC agregar_pelicula ?, ?, ?";
    $params = array($pelicula, $fecha_inicio, $fecha_limit);

    // Intentar ejecutar la consulta
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt) {
        echo "<div class='alert alert-success'>Película agregada correctamente sin imagen.</div>";
        header("Location: cartelera.php");
    } else {
        echo "<div class='alert alert-danger'>Error al agregar la película.</div>";
    }
}
?>

<div class="container mt-4">
    <h2>Agregar Película</h2>
    <form method="POST">
        <div class="form-group">
            <label for="pelicula">Nombre de la Película:</label>
            <input type="text" class="form-control" id="pelicula" name="pelicula" required>
        </div>
        <div class="form-group">
            <label for="fecha_inicio">Fecha de Inicio:</label>
            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
        </div>
        <div class="form-group">
            <label for="fecha_limit">Fecha Límite:</label>
            <input type="date" class="form-control" id="fecha_limit" name="fecha_limit" required>
        </div>
        <button type="submit" class="btn btn-primary">Agregar Película</button>
        <button type="button" class="btn btn-secondary" onclick="location.href='cartelera.php'">Cancelar</button>
    </form>
</div>

<?php
include 'footer.php'; // Incluir el footer
?>
