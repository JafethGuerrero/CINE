<?php
include("header.php"); // Verifica que este archivo exista en la ruta correcta
include("conexion.php");

// Recibimos el id por URL
$dato = isset($_GET['id']) ? $_GET['id'] : null;

if ($dato === null) {
    die("No se proporcionó un ID de almacén.");
}

// Usar el procedimiento almacenado para buscar el almacén
$sql = "EXEC sp_search_almacenuno ?";
$params = array($dato);
$res = sqlsrv_query($conn, $sql, $params);

// Verificar si la consulta se ejecutó correctamente
if ($res === false) {
    die("Error en la consulta: " . print_r(sqlsrv_errors(), true));
}

$post = sqlsrv_fetch_array($res);

if ($post === null) {
    die("No se encontró el almacén con el ID proporcionado.");
}
?>
<html>
<head>
    <title>Actualizar Almacén</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="row col mt-5">
        <form method="post" class="form-control">
            <fieldset class="form-group">
                <legend><b>Actualizar Datos del Almacén</b></legend>
                <hr>

                <label for="id_almacen" class="form-label">ID Almacén (*)</label>
                <input class="form-control" type="text" name="id_almacen" required autocomplete="off" value="<?php echo htmlspecialchars($post['id_almacen']); ?>" disabled>

                <label for="cantidad" class="form-label">Cantidad (*)</label>
                <input class="form-control" type="number" name="cantidad" value="<?php echo htmlspecialchars($post['cantidad']); ?>" required>

                <label for="tipo_almacenamiento" class="form-label">Tipo Almacenamiento (*)</label>
                <input class="form-control" type="text" name="tipo_almacenamiento" value="<?php echo htmlspecialchars($post['tipo_almacenamiento']); ?>" required>

                <label for="fecha_reabastecimiento" class="form-label">Fecha Reabastecimiento (*)</label>
                <input class="form-control" type="text" name="fecha_reabastecimiento" value="<?php echo htmlspecialchars($post['fecha_reabastecimiento']); ?>" required>

                <hr>

                <input class="btn btn-success" type="submit" name="update" value="Actualizar Datos" required>
                <a href="almacen.php" class="btn btn-secondary"> Salir</a>
            </fieldset>

            <?php
            if (isset($_POST['update'])) {
                // Recoger los datos del formulario
                $cantidad = $_POST['cantidad'];
                $tipo_almacenamiento = strtoupper($_POST['tipo_almacenamiento']);
                $fecha_reabastecimiento = $_POST['fecha_reabastecimiento'];
                $id_almacen = $post['id_almacen']; // Usar el id_almacen original

                // Preparar la consulta para llamar al procedimiento almacenado de actualización
                $query = "EXEC sp_update_almacen ?, ?, ?, ?";
                $params_update = array($cantidad, $tipo_almacenamiento, $fecha_reabastecimiento, $id_almacen);

                // Ejecutar el procedimiento almacenado
                $recurso = sqlsrv_query($conn, $query, $params_update);

                if ($recurso) {
                    echo "<br><br>
                          <div class='alert alert-success alert-dismissible'>
                              <a href='./almacen.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                              <strong>¡Excelente!</strong> Registro actualizado exitosamente.
                          </div>";
                } else {
                    echo "<br><br>
                          <div class='alert alert-danger alert-dismissible'>
                              <a href='./almacen.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                              <strong>Error!</strong> No se actualizó el registro. " . print_r(sqlsrv_errors(), true) . "
                          </div>";
                }
            }
            ?>
        </form>
    </div>
</div>
</body>
</html>
