<?php
include("header.php");
include("conexion.php");

// Recibimos el id por URL
$dato = isset($_GET['id']) ? $_GET['id'] : null;

if ($dato === null) {
    die("No se proporcionó un ID de producto.");
}

// Usar el procedimiento almacenado para buscar el producto
$sql = "EXEC sp_search_productouno ?";
$params = array($dato);
$res = sqlsrv_query($conn, $sql, $params);

// Verificar si la consulta se ejecutó correctamente
if ($res === false) {
    die("Error en la consulta de producto: " . print_r(sqlsrv_errors(), true));
}

$post = sqlsrv_fetch_array($res);

if ($post === null) {
    die("No se encontró el producto con el ID proporcionado.");
}
?>

<html>
<head>
    <title>Actualizar Producto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="row col mt-5">
        <form method="post" class="form-control">
            <fieldset class="form-group">
                <legend><b>Actualizar Datos del Producto</b></legend>
                <hr>

                <label for="id_producto" class="form-label">ID Producto (*)</label>
                <input class="form-control" type="text" name="id_producto" required autocomplete="off" value="<?php echo htmlspecialchars($post['id_producto']); ?>" disabled>

                <label for="nombre_producto" class="form-label">Nombre Producto (*)</label>
                <input class="form-control" type="text" name="nombre_producto" value="<?php echo htmlspecialchars($post['nombre_producto']); ?>" required>

                <label for="descripcion" class="form-label">Descripción (*)</label>
                <textarea class="form-control" name="descripcion" required><?php echo htmlspecialchars($post['descripcion']); ?></textarea>

                <label for="fecha_creacion" class="form-label">Fecha de Creación (*)</label>
                <input class="form-control" type="date" name="fecha_creacion" value="<?php echo htmlspecialchars($post['fecha_creacion']->format('Y-m-d')); ?>" required>

                <label for="fecha_caducidad" class="form-label">Fecha de Caducidad (*)</label>
                <input class="form-control" type="date" name="fecha_caducidad" value="<?php echo htmlspecialchars($post['fecha_caducidad']->format('Y-m-d')); ?>" required>
                
                <!-- Categoría -->
                <label for="categoria" class="form-label">Categoría (*)</label>
                <select class="form-select" name="categoria" required>
                    <option value="">Seleccione una opción</option>
                    <?php
                    // Realizamos la consulta a la base de datos para obtener las categorías
                    $queryCategorias = "SELECT id_categoria, nombre_categoria FROM Categorias"; 
                    $resultCategorias = sqlsrv_query($conn, $queryCategorias);

                    if ($resultCategorias === false) {
                        die("Error en la consulta de categorías: " . print_r(sqlsrv_errors(), true));
                    }

                    // Mostrar las categorías disponibles
                    while ($categoria = sqlsrv_fetch_array($resultCategorias, SQLSRV_FETCH_ASSOC)) {
                        $selected = ($categoria['id_categoria'] == $post['id_categoria']) ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($categoria['id_categoria']) . "' $selected>" . htmlspecialchars($categoria['nombre_categoria']) . "</option>";
                    }
                    ?>
                </select>
                <hr>

                <!-- Botones de actualizar y cancelar -->
                <div class="d-flex justify-content-between">
                    <input class="btn btn-success btn-sm" type="submit" name="update" value="Actualizar Datos">
                    <a href="productos.php" class="btn btn-secondary btn-sm">Cancelar</a>
                </div>
            </fieldset>
        </form>

        <?php
        // Verificar si el formulario ha sido enviado
        if (isset($_POST['update'])) {
            // Recoger los datos del formulario
            $nombre_producto = strtoupper(trim($_POST['nombre_producto']));
            $descripcion = trim($_POST['descripcion']);
            $fecha_creacion = $_POST['fecha_creacion'];
            $fecha_caducidad = $_POST['fecha_caducidad'];
            $id_producto = $post['id_producto']; // Usar el id_producto original
            $categoria = $_POST['categoria']; // Obtener la categoría seleccionada

            // Preparar la consulta para llamar al procedimiento almacenado de actualización
            $query = "EXEC sp_update_producto ?, ?, ?, ?, ?, ?";
            $params_update = array($nombre_producto, $descripcion, $fecha_creacion, $fecha_caducidad, $categoria, $id_producto);

            // Ejecutar el procedimiento almacenado
            $recurso = sqlsrv_query($conn, $query, $params_update);

            if ($recurso) {
               header("Location: productos.php");
               exit();
            } else {
                echo "<br><br>
                      <div class='alert alert-danger alert-dismissible'>
                          <a href='./productos.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                          <strong>Error!</strong> No se actualizó el registro. " . print_r(sqlsrv_errors(), true) . "
                      </div>";
            }
        }
        ?>
    </div>
</div>
</body>
</html>
