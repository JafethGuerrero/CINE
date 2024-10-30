<?php
include("header.php"); // Verifica que este archivo exista en la ruta correcta
include("conexion.php");
include "footer.php"; // Incluimos el footer

// Recibimos el id por URL
$dato = isset($_GET['id']) ? $_GET['id'] : null;

if ($dato === null) {
    die("No se proporcionó un ID de proveedor.");
}

// Usar el procedimiento almacenado para buscar el proveedor
$sql = "EXEC sp_search_proveedor ?";
$params = array($dato);
$res = sqlsrv_query($conn, $sql, $params);

// Verificar si la consulta se ejecutó correctamente
if ($res === false) {
    die("Error en la consulta: " . print_r(sqlsrv_errors(), true));
}

$post = sqlsrv_fetch_array($res);

if ($post === null) {
    die("No se encontró el proveedor con el ID proporcionado.");
}
?>
<html>
<head>
    <title>Eliminar Proveedor</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
  <div class="container">
    <div class="row col mt-5">
          <form method="post" class="form-control">

            <fieldset class="form-group">
              <legend><b>Eliminar Datos del Proveedor</b></legend><hr>

              <label for="id_proveedor" class="form-label">ID Proveedor (*)</label>
              <input class="form-control" type="text" name="id_proveedor" required autocomplete="off" value="<?php echo htmlspecialchars($post['id_proveedor']); ?>" disabled>

              <label for="nombre_proveedor" class="form-label">Nombre Proveedor (*)</label>
              <input class="form-control" type="text" name="nombre_proveedor" value="<?php echo htmlspecialchars($post['nombre_proveedor']); ?>" disabled>

              <label for="contacto" class="form-label">Contacto (*)</label>
              <input class="form-control" type="text" name="contacto" value="<?php echo htmlspecialchars($post['contacto']); ?>" disabled>

              <label for="telefono" class="form-label">Teléfono (*)</label>
              <input class="form-control" type="text" name="telefono" value="<?php echo htmlspecialchars($post['telefono']); ?>" disabled>

              <label for="email" class="form-label">Email (*)</label>
              <input class="form-control" type="email" name="email" value="<?php echo htmlspecialchars($post['email']); ?>" disabled>

              <hr>

              <input class="btn btn-danger" type="submit" name="delete" value="Eliminar Datos" required>
              <a href="proveedores.php" class="btn btn-secondary"> Salir</a>
            </fieldset>

          <?php
            if (isset($_POST['delete'])) {
                // Usar el procedimiento almacenado para eliminar
                $query = "EXEC sp_delete_proveedor ?";
                $params_delete = array($dato);
                $res_delete = sqlsrv_query($conn, $query, $params_delete);

                if ($res_delete) {
                    echo "<br><br>
                          <div class='alert alert-success alert-dismissible'>
                              <a href='./proveedores.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                              <strong>¡Excelente!</strong> Proveedor eliminado exitosamente.
                          </div>";
                } else {
                    echo "<br><br>
                          <div class='alert alert-danger alert-dismissible'>
                              <a href='./proveedores.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                              <strong>Error!</strong> No se pudo eliminar el proveedor. " . print_r(sqlsrv_errors(), true) . "
                          </div>";
                }
            }
          ?>
        </form>
      </div>
  </div>
</body>
</html>
