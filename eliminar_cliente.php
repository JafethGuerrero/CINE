<?php
include("header.php"); // Verifica que este archivo exista en la ruta correcta
include("conexion.php");

/* Recibimos el id por URL */
$dato = isset($_GET['id']) ? $_GET['id'] : null;

if ($dato === null) {
    die("No se proporcionó un ID de cliente.");
}

/* Buscamos ese registro tomando en cuenta el id_cliente */
$sql = "SELECT * FROM clientes WHERE id_cliente = ?";
$params = array($dato);

$res = sqlsrv_query($conn, $sql, $params);

// Verificar si la consulta se ejecutó correctamente
if ($res === false) {
    die("Error en la consulta: " . print_r(sqlsrv_errors(), true));
}

$post = sqlsrv_fetch_array($res);

if ($post === null) {
    die("No se encontró el cliente con el ID proporcionado.");
}

// Formatear el número de teléfono
$phone = $post['celular'];
$formatted_phone = (strlen($phone) == 12) ? substr($phone, 0, 3) . '-' . substr($phone, 3) : $phone;
?>
<html>
<head>
    <title>Eliminar Cliente</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
  <div class="container">
    <div class="row col mt-5">
          <form method="post" class="form-control">

            <fieldset class="form-group">
              <legend><b>Eliminar Datos del Cliente</b></legend><hr>

              <label for="id_cliente" class="form-label">ID Cliente (*)</label>
              <input class="form-control" type="text" name="id_cliente" required autocomplete="off" value="<?php echo htmlspecialchars($post['id_cliente']); ?>" disabled>

              <label for="nombre" class="form-label">Nombre del Cliente (*)</label>
              <input class="form-control" type="text" name="nombre" value="<?php echo htmlspecialchars($post['nombre']); ?>" disabled>

              <label for="correo_electronico" class="form-label">Correo Electrónico (*)</label>
              <input class="form-control" type="email" name="correo_electronico" value="<?php echo htmlspecialchars($post['correo_electronico']); ?>" disabled>

              <label for="celular" class="form-label">Número de Celular (*)</label>
              <input class="form-control" type="text" name="celular" value="<?php echo htmlspecialchars($formatted_phone); ?>" disabled>

              <label for="cuenta_bancaria" class="form-label">Cuenta Bancaria (*)</label>
              <input class="form-control" type="text" name="cuenta_bancaria" value="<?php echo htmlspecialchars($post['cuenta_bancaria']); ?>" disabled>

              <hr>

              <input class="btn btn-danger" type="submit" name="delete" value="Eliminar Datos" required>
              <a href="clientes.php" class="btn btn-secondary"> Salir</a>
            </fieldset>

          <?php
            if (isset($_POST['delete'])) {
                $query = "DELETE FROM clientes WHERE id_cliente = ?";
                $params_delete = array($dato);

                $recurso = sqlsrv_prepare($conn, $query, $params_delete);

                if (sqlsrv_execute($recurso)) {
                    echo "<br><br>
                          <div class='alert alert-success alert-dismissible'>
                              <a href='./listClientes.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                              <strong>¡Excelente!</strong> Registro eliminado exitosamente.
                          </div>";
                } else {
                    echo "<br><br>
                          <div class='alert alert-danger alert-dismissible'>
                              <a href='./listClientes.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                              <strong>Error!</strong> No se eliminó el registro. " . print_r(sqlsrv_errors(), true) . "
                          </div>";
                }
            }
          ?>
        </form>
      </div>
  </div>
</body>
<footer style="text-align:center;">© Cine - <?php echo date("Y");?></footer> 
</html>
