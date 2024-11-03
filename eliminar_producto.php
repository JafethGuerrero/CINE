<?php
include("header.php"); // Verifica que este archivo exista en la ruta correcta
include("conexion.php");
include "footer.php"; // Incluimos el footer

// Recibimos el id por URL
$dato = isset($_GET['id']) ? $_GET['id'] : null;

if ($dato === null) {
    die("No se proporcionó un ID de producto.");
}

// Usar el procedimiento almacenado para buscar el producto
$sql = "EXEC sp_search_producto ?";
$params = array($dato);
$res = sqlsrv_query($conn, $sql, $params);

// Verificar si la consulta se ejecutó correctamente
if ($res === false) {
    die("Error en la consulta: " . print_r(sqlsrv_errors(), true));
}

$post = sqlsrv_fetch_array($res);

if ($post === null) {
    die("No se encontró el producto con el ID proporcionado.");
}
?>
<html>
<head>
    <title>Eliminar Producto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
  <div class="container">
    <div class="row col mt-5">
          <form method="post" class="form-control">

            <fieldset class="form-group">
              <legend><b>Eliminar Datos del Producto</b></legend><hr>

              <label for="id_producto" class="form-label">ID Producto (*)</label>
              <input class="form-control" type="text" name="id_producto" required autocomplete="off" value="<?php echo htmlspecialchars($post['id_producto']); ?>" disabled>

              <label for="nombre_producto" class="form-label">Nombre Producto (*)</label>
              <input class="form-control" type="text" name="nombre_producto" value="<?php echo htmlspecialchars($post['nombre_producto']); ?>" disabled>

              <label for="descripcion" class="form-label">Descripción (*)</label>
              <input class="form-control" type="text" name="descripcion" value="<?php echo htmlspecialchars($post['descripcion']); ?>" disabled>

              <label for="fecha_creacion" class="form-label">Fecha de Creación (*)</label>
              <input class="form-control" type="date" name="fecha_creacion" value="<?php echo htmlspecialchars($post['fecha_creacion']->format('Y-m-d')); ?>" disabled>

              <label for="fecha_caducidad" class="form-label">Fecha de Caducidad (*)</label>
              <input class="form-control" type="date" name="fecha_caducidad" value="<?php echo htmlspecialchars($post['fecha_caducidad']->format('Y-m-d')); ?>" disabled>

              <hr>

              <input class="btn btn-danger" type="submit" name="delete" value="Eliminar Datos" required>
              <a href="productos.php" class="btn btn-secondary"> Salir</a>
            </fieldset>

          <?php
            if (isset($_POST['delete'])) {
                // Usar el procedimiento almacenado para eliminar
                $query = "EXEC sp_delete_producto ?";
                $params_delete = array($dato);
                $res_delete = sqlsrv_query($conn, $query, $params_delete);

                if ($res_delete) {
                    echo "<br><br>
                          <div class='alert alert-success alert-dismissible'>
                              <a href='./productos.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                              <strong>¡Excelente!</strong> Producto eliminado exitosamente.
                          </div>";
                } else {
                    echo "<br><br>
                          <div class='alert alert-danger alert-dismissible'>
                              <a href='./productos.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                              <strong>Error!</strong> No se pudo eliminar el producto. " . print_r(sqlsrv_errors(), true) . "
                          </div>";
                }
            }
          ?>
        </form>
      </div>
  </div>
</body>
</html>
