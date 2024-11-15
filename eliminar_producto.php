<?php
include("header.php"); // Verifica que este archivo exista en la ruta correcta
include("conexion.php");
include "footer.php"; // Incluimos el footer

// Recibimos el id por URL
$dato = isset($_GET['id']) ? $_GET['id'] : null;

if ($dato === null) {
    die("No se proporcionó un ID de categoría.");
}

// Usar el procedimiento almacenado para buscar la categoría
$sql = "EXEC sp_search_categoria ?";
$params = array($dato);
$res = sqlsrv_query($conn, $sql, $params);

// Verificar si la consulta se ejecutó correctamente
if ($res === false) {
    die("Error en la consulta: " . print_r(sqlsrv_errors(), true));
}

$post = sqlsrv_fetch_array($res);

if ($post === null) {
    die("No se encontró la categoría con el ID proporcionado.");
}
?>
<html>
<head>
    <title>Eliminar Categoría</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
  <div class="container">
    <div class="row col mt-5">
          <form method="post" class="form-control">

            <fieldset class="form-group">
              <legend><b>Eliminar Datos de la Categoría</b></legend><hr>

              <label for="id_categoria" class="form-label">ID Categoría (*)</label>
              <input class="form-control" type="text" name="id_categoria" required autocomplete="off" value="<?php echo htmlspecialchars($post['id_categoria']); ?>" disabled>

              <label for="nombre_categoria" class="form-label">Nombre Categoría (*)</label>
              <input class="form-control" type="text" name="nombre_categoria" value="<?php echo htmlspecialchars($post['nombre']); ?>" disabled>

              <hr>

              <input class="btn btn-danger" type="submit" name="delete" value="Eliminar Datos" required>
              <a href="categorias.php" class="btn btn-secondary"> Salir</a>
            </fieldset>

          <?php
            if (isset($_POST['delete'])) {
                // Usar el procedimiento almacenado para eliminar
                $query = "EXEC sp_delete_categoria ?";
                $params_delete = array($dato);
                $res_delete = sqlsrv_query($conn, $query, $params_delete);

                if ($res_delete) {
                    echo "<br><br>
                          <div class='alert alert-success alert-dismissible'>
                              <a href='./categorias.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                              <strong>¡Excelente!</strong> Categoría eliminada exitosamente.
                          </div>";
                } else {
                    echo "<br><br>
                          <div class='alert alert-danger alert-dismissible'>
                              <a href='./categorias.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                              <strong>Error!</strong> No se pudo eliminar la categoría. " . print_r(sqlsrv_errors(), true) . "
                          </div>";
                }
            }
          ?>
        </form>
      </div>
  </div>
</body>
</html>
