<?php
session_start();
include("header.php"); // Verifica que este archivo exista en la ruta correcta
include("conexion.php");
include "footer.php"; // Incluimos el footer

// Recibimos el id por URL
$dato = isset($_GET['id']) ? $_GET['id'] : null;

if ($dato === null) {
    die("No se proporcionó un ID de sala.");
}

// Usar el procedimiento almacenado para buscar la sala
$sql = "EXEC sp_search_sala ?"; // Asegúrate de tener este procedimiento almacenado
$params = array($dato);
$res = sqlsrv_query($conn, $sql, $params);

// Verificar si la consulta se ejecutó correctamente
if ($res === false) {
    die("Error en la consulta: " . print_r(sqlsrv_errors(), true));
}

$post = sqlsrv_fetch_array($res);

if ($post === null) {
    die("No se encontró la sala con el ID proporcionado.");
}
?>
<html>
<head>
    <title>Editar Sala</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row col mt-5">
            <form method="post" class="form-control">

                <fieldset class="form-group">
                    <legend><b>Editar Datos de la Sala</b></legend><hr>

                    <label for="id_salas" class="form-label">ID Sala (*)</label>
                    <input class="form-control" type="text" name="id_salas" required autocomplete="off" value="<?php echo htmlspecialchars($post['id_salas']); ?>" disabled>

                    <label for="nombre" class="form-label">Nombre de la Sala (*)</label>
                    <input class="form-control" type="text" name="nombre" value="<?php echo htmlspecialchars($post['nombre']); ?>" required>

                    <label for="cantidad_asientos" class="form-label">Cantidad de Asientos (*)</label>
                    <input class="form-control" type="number" name="cantidad_asientos" value="<?php echo htmlspecialchars($post['cantidad_asientos']); ?>" required>

                    <label for="tipo_proyeccion" class="form-label">Tipo de Proyección (*)</label>
                    <input class="form-control" type="text" name="tipo_proyeccion" value="<?php echo htmlspecialchars($post['tipo_proyeccion']); ?>" required>

                    <label for="pelicula" class="form-label">Película (*)</label>
                    <select class="form-select" name="id_pelicula" required>
                        <option value="">Seleccione una película</option>
                        <?php 
                        // Consulta para obtener las películas
                        $sqlPeliculas = "SELECT id_pelicula, pelicula FROM cartelera"; 
                        $resultPeliculas = sqlsrv_query($conn, $sqlPeliculas);

                        if ($resultPeliculas === false) {
                            die(print_r(sqlsrv_errors(), true));
                        }

                        // Mostrar las películas en el dropdown
                        while ($pelicula = sqlsrv_fetch_array($resultPeliculas, SQLSRV_FETCH_ASSOC)): ?>
                            <option value="<?php echo htmlspecialchars($pelicula['id_pelicula']); ?>" <?php echo ($pelicula['id_pelicula'] == $post['id_pelicula']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($pelicula['pelicula']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>

                    <hr>

                    <input class="btn btn-success" type="submit" name="update" value="Actualizar Datos" required>
                    <a href="salas.php" class="btn btn-secondary"> Salir</a>
                </fieldset>

                <?php
                if (isset($_POST['update'])) {
                    // Usar el procedimiento almacenado para actualizar
                    $nombre = $_POST['nombre'];
                    $cantidad_asientos = $_POST['cantidad_asientos'];
                    $tipo_proyeccion = $_POST['tipo_proyeccion'];
                    $id_pelicula = $_POST['id_pelicula'];

                    $query = "EXEC sp_update_sala ?, ?, ?, ?, ?";
                    $params_update = array($dato, $nombre, $cantidad_asientos, $tipo_proyeccion, $id_pelicula);
                    $res_update = sqlsrv_query($conn, $query, $params_update);

                    if ($res_update) {
                        echo "<br><br>
                              <div class='alert alert-success alert-dismissible'>
                                  <a href='./salas.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                  <strong>¡Excelente!</strong> Sala actualizada exitosamente.
                              </div>";
                    } else {
                        echo "<br><br>
                              <div class='alert alert-danger alert-dismissible'>
                                  <a href='./salas.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                  <strong>Error!</strong> No se pudo actualizar la sala. " . print_r(sqlsrv_errors(), true) . "
                              </div>";
                    }
                }
                ?>
            </form>
        </div>
    </div>
</body>
</html>
