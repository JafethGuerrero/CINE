<?php
include("header.php"); // Verifica que este archivo exista en la ruta correcta
include("conexion.php");
include "footer.php"; // Incluimos el footer

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
    <title>Eliminar Almacén</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="row col mt-5">
        <form method="post" class="form-control">
            <fieldset class="form-group">
                <legend><b>Eliminar Datos del Almacén</b></legend>
                <hr>

                <label for="id_almacen" class="form-label">ID Almacén (*)</label>
                <input class="form-control" type="text" name="id_almacen" required autocomplete="off" value="<?php echo htmlspecialchars($post['id_almacen']); ?>" disabled>

                <label for="cantidad" class="form-label">Cantidad (*)</label>
                <input class="form-control" type="number" name="cantidad" value="<?php echo htmlspecialchars($post['cantidad']); ?>" disabled>

                <label for="tipo_almacenamiento" class="form-label">Tipo Almacenamiento (*)</label>
                <input class="form-control" type="text" name="tipo_almacenamiento" value="<?php echo htmlspecialchars($post['tipo_almacenamiento']); ?>" disabled>

                <label for="fecha_reabastecimiento" class="form-label">Fecha Reabastecimiento (*)</label>
                <input class="form-control" type="text" name="fecha_reabastecimiento" value="<?php echo htmlspecialchars($post['fecha_reabastecimiento']); ?>" disabled>

                <hr>

                <input class="btn btn-danger" type="submit" name="delete" value="Eliminar Datos" required>
                <a href="almacen.php" class="btn btn-secondary"> Salir</a>
            </fieldset>

            <?php
            if (isset($_POST['delete'])) {
                // Usar el procedimiento almacenado para eliminar
                $query = "EXEC sp_delete_almacen ?";
                $params_delete = array($dato);
                $res_delete = sqlsrv_query($conn, $query, $params_delete);

                if ($res_delete) {
                    echo "<br><br>
                          <div class='alert alert-success alert-dismissible'>
                              <a href='./almacen.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                              <strong>¡Excelente!</strong> Almacén eliminado exitosamente.
                          </div>";
                } else {
                    echo "<br><br>
                          <div class='alert alert-danger alert-dismissible'>
                              <a href='./almacen.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                              <strong>Error!</strong> No se pudo eliminar el almacén. " . print_r(sqlsrv_errors(), true) . "
                          </div>";
                }
            }
            ?>
        </form>
    </div>
</div>
</body>
</html>
