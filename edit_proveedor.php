<?php
include("header.php"); // Verifica que este archivo exista en la ruta correcta
include("conexion.php");

// Recibimos el id por URL
$dato = isset($_GET['id']) ? $_GET['id'] : null;

if ($dato === null) {
    die("No se proporcionó un ID de proveedor.");
}

// Usar el procedimiento almacenado para buscar el proveedor
$sql = "EXEC sp_search_proveedoruno ?";
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
    <title>Actualizar Proveedor</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="row col mt-5">
        <form method="post" class="form-control">
            <fieldset class="form-group">
                <legend><b>Actualizar Datos del Proveedor</b></legend>
                <hr>

                <label for="id_proveedor" class="form-label">ID Proveedor (*)</label>
                <input class="form-control" type="text" name="id_proveedor" required autocomplete="off" value="<?php echo htmlspecialchars($post['id_proveedor']); ?>" disabled>

                <label for="nombre_proveedor" class="form-label">Nombre del Proveedor (*)</label>
                <input class="form-control" type="text" name="nombre_proveedor" value="<?php echo htmlspecialchars($post['nombre_proveedor']); ?>" required>

                <label for="contacto" class="form-label">Contacto (*)</label>
                <input class="form-control" type="text" name="contacto" value="<?php echo htmlspecialchars($post['contacto']); ?>" required>

                <label for="telefono" class="form-label">Teléfono (*)</label>
                <input class="form-control" type="tel" name="telefono" value="<?php echo htmlspecialchars($post['telefono']); ?>" required>

                <label for="email" class="form-label">Email (*)</label>
                <input class="form-control" type="email" name="email" value="<?php echo htmlspecialchars($post['email']); ?>" required>

                <hr>

                <input class="btn btn-success" type="submit" name="update" value="Actualizar Datos" required>
                <a href="proveedores.php" class="btn btn-secondary"> Salir</a>
            </fieldset>

            <?php
            if (isset($_POST['update'])) {
                // Recoger los datos del formulario
                $nombre_proveedor = strtoupper($_POST['nombre_proveedor']);
                $contacto = $_POST['contacto'];
                $telefono = $_POST['telefono'];
                $email = $_POST['email'];
                $id_proveedor = $post['id_proveedor']; // Usar el id_proveedor original

                // Preparar la consulta para llamar al procedimiento almacenado de actualización
                $query = "EXEC sp_update_proveedor ?, ?, ?, ?, ?";
                $params_update = array($nombre_proveedor, $contacto, $telefono, $email, $id_proveedor);

                // Ejecutar el procedimiento almacenado
                $recurso = sqlsrv_query($conn, $query, $params_update);

                if ($recurso) {
                    echo "<br><br>
                          <div class='alert alert-success alert-dismissible'>
                              <a href='./proveedores.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                              <strong>¡Excelente!</strong> Registro actualizado exitosamente.
                          </div>";
                } else {
                    echo "<br><br>
                          <div class='alert alert-danger alert-dismissible'>
                              <a href='./proveedores.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
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
