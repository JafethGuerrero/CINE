<?php
include("header.php"); // Verifica que este archivo exista en la ruta correcta
include("conexion.php");

/* Recibimos el id por URL */
$dato = isset($_GET['id']) ? $_GET['id'] : null;

if ($dato === null) {
    die("No se proporcionó un ID de empleado.");
}

/* Buscamos ese registro tomando en cuenta el id_empleado */
$sql = "SELECT * FROM empleados WHERE id_empleado = ?";
$params = array($dato);

$res = sqlsrv_query($conn, $sql, $params);

// Verificar si la consulta se ejecutó correctamente
if ($res === false) {
    die("Error en la consulta: " . print_r(sqlsrv_errors(), true));
}

$post = sqlsrv_fetch_array($res);

if ($post === null) {
    die("No se encontró el empleado con el ID proporcionado.");
}
?>
<html>
<head>
    <title>Eliminar Empleado</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
  <div class="container">
    <div class="row col mt-5">
          <form method="post" class="form-control">

            <fieldset class="form-group">
              <legend><b>Eliminar Datos del Empleado</b></legend><hr>

              <label for="id_empleado" class="form-label">ID Empleado</label>
              <input class="form-control" type="text" name="id_empleado" value="<?php echo htmlspecialchars($post['id_empleado']); ?>" disabled>

              <label for="nombre" class="form-label">Nombre del Empleado</label>
              <input class="form-control" type="text" name="nombre" value="<?php echo htmlspecialchars($post['nombre']); ?>" disabled>

              <label for="puesto" class="form-label">Puesto</label>
              <input class="form-control" type="text" name="puesto" value="<?php echo htmlspecialchars($post['puesto']); ?>" disabled>

              <label for="contraseña" class="form-label">Contraseña</label>
              <input class="form-control" type="text" name="contraseña" value="********" disabled>

              <label for="fecha_contratacion" class="form-label">Fecha de Contratación</label>
              <input class="form-control" type="date" name="fecha_contratacion" value="<?php echo $post['fecha_contratacion'] ? htmlspecialchars($post['fecha_contratacion']->format('Y-m-d')) : ''; ?>" disabled>

              <label for="fecha_baja" class="form-label">Fecha Baja</label>
              <input class="form-control" type="date" name="fecha_baja" value="<?php echo $post['fecha_baja'] ? htmlspecialchars($post['fecha_baja']->format('Y-m-d')) : ''; ?>" disabled>

              <label for="salario" class="form-label">Salario</label>
              <input class="form-control" type="text" name="salario" value="<?php echo htmlspecialchars($post['salario']); ?>" disabled>

              <hr>

              <p>¿Estás seguro de que deseas eliminar este empleado?</p>
              <input class="btn btn-danger" type="submit" name="delete" value="Eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar este empleado?');">
              <a href="empleados.php" class="btn btn-secondary"> Salir</a>
            </fieldset>

          <?php
            if (isset($_POST['delete'])) {
                // Preparar la consulta de eliminación
                $query = "DELETE FROM empleados WHERE id_empleado = ?";
                $params_delete = array($dato);

                $recurso = sqlsrv_prepare($conn, $query, $params_delete);

                if (sqlsrv_execute($recurso)) {
                    echo "<br><br>
                          <div class='alert alert-success alert-dismissible'>
                              <a href='./empleados.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                              <strong>¡Excelente!</strong> Registro eliminado exitosamente.
                          </div>";
                } else {
                    echo "<br><br>
                          <div class='alert alert-danger alert-dismissible'>
                              <a href='./empleados.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                              <strong>Error!</strong> No se pudo eliminar el registro. " . print_r(sqlsrv_errors(), true) . "
                          </div>";
                }
            }
          ?>
        </form>
      </div>
  </div>
</body>
<footer style="text-align:center;">© Cine 2024 - <?php echo date("Y");?></footer> 
</html>
