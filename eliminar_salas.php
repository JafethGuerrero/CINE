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

$post = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC); // Asegúrate de usar el modo de fetch correcto

if ($post === null) {
    die("No se encontró la sala con el ID proporcionado.");
}
?>
<html>
<head>
    <title>Eliminar Sala</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
  <div class="container">
    <div class="row col mt-5">
          <form method="post" class="form-control">

            <fieldset class="form-group">
              <legend><b>Eliminar Datos de la Sala</b></legend><hr>

              <label for="id_salas" class="form-label">ID Sala (*)</label>
              <input class="form-control" type="text" name="id_salas" required autocomplete="off" value="<?php echo htmlspecialchars($post['id_salas']); ?>" disabled>

              <label for="nombre" class="form-label">Nombre de la Sala (*)</label>
              <input class="form-control" type="text" name="nombre" value="<?php echo htmlspecialchars($post['nombre']); ?>" required disabled> <!-- Se añade 'disabled' para evitar la edición -->

              <label for="cantidad_asientos" class="form-label">Cantidad de Asientos (*)</label>
              <input class="form-control" type="number" name="cantidad_asientos" value="<?php echo htmlspecialchars($post['cantidad_asientos']); ?>" required disabled> <!-- Se añade 'disabled' para evitar la edición -->

              <label for="tipo_proyeccion" class="form-label">Tipo de Proyección (*)</label>
              <input class="form-control" type="text" name="tipo_proyeccion" value="<?php echo htmlspecialchars($post['tipo_proyeccion']); ?>" required disabled> <!-- Se añade 'disabled' para evitar la edición -->

              <hr>

              <input class="btn btn-danger" type="submit" name="delete" value="Eliminar Datos" required>
              <a href="salas.php" class="btn btn-secondary"> Salir</a>
            </fieldset>

          <?php
            if (isset($_POST['delete'])) {
                // Usar el procedimiento almacenado para eliminar
                $query = "EXEC sp_delete_sala ?"; // Asegúrate de que este procedimiento existe
                $params_delete = array($dato);
                $res_delete = sqlsrv_query($conn, $query, $params_delete);

                if ($res_delete) {
                    // Redirigir a la página de salas con un mensaje de éxito
                    $_SESSION['status'] = [
                        'type' => 'success',
                        'message' => 'Sala eliminada exitosamente.'
                    ];
                    header("Location: salas.php");
                    exit();
                } else {
                    echo "<br><br>
                          <div class='alert alert-danger alert-dismissible'>
                              <a href='./salas.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                              <strong>Error!</strong> No se pudo eliminar la sala. " . print_r(sqlsrv_errors(), true) . "
                          </div>";
                }
            }
          ?>
        </form>
      </div>
  </div>
</body>
</html>
