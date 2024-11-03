<?php
include 'conexion.php'; // Incluir el archivo de conexión
include 'headeralm.php'; // Incluir el encabezado
include 'footer.php'; // Incluir el footer

// Verificar si se ha enviado un término de búsqueda
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Modificar la consulta SQL para incluir la búsqueda
$sql = "SELECT id_producto, nombre_producto, descripcion, fecha_creacion, fecha_caducidad FROM Productos";
if (!empty($searchTerm)) {
    $sql .= " WHERE nombre_producto LIKE ? OR descripcion LIKE ?";
}

// Preparar y ejecutar la consulta
$params = [];
if (!empty($searchTerm)) {
    $params = ["%$searchTerm%", "%$searchTerm%"];
}

// Ejecutar la consulta
$stmt = sqlsrv_query($conn, $sql, $params);

// Comprobar si la consulta fue exitosa
if ($stmt === false) {
    // Mostrar el error de SQL Server
    die(print_r(sqlsrv_errors(), true));
}
?>

<div class="container mt-5">
    <h2 class="text-center">Lista de Productos</h2>

    <div class="mb-3 text-center">
        <a href="almacen.php" class="btn btn-info btn-animate">Ver Almacen</a>
        <a href="proveedores.php" class="btn btn-warning btn-animate">Ver Proveedores</a>
    </div>
    
    <!-- Formulario de búsqueda -->
    <form id="search-form" class="mb-4">
        <div class="input-group">
            <input type="text" id="search" name="search" class="form-control" placeholder="Buscar productos..." value="<?php echo htmlspecialchars($searchTerm); ?>">
            <div class="input-group-append">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </div>
    </form>

    <div id="results">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre Producto</th>
                    <th>Descripción</th>
                    <th>Fecha Creación</th>
                    <th>Fecha Caducidad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id_producto']); ?></td>
                        <td><?php echo htmlspecialchars($row['nombre_producto']); ?></td>
                        <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                        <td><?php echo htmlspecialchars($row['fecha_creacion'] ? $row['fecha_creacion']->format('Y-m-d') : 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($row['fecha_caducidad'] ? $row['fecha_caducidad']->format('Y-m-d') : 'N/A'); ?></td>
                        <td class="text-center">
                            <a href="edit_producto.php?id=<?php echo $row['id_producto']; ?>" class="btn btn-default" title="Modificar">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <a href="eliminar_producto.php?id=<?php echo $row['id_producto']; ?>" class="btn btn-default" title="Eliminar">
                                <i class="fa fa-remove"></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <a href="alta_producto.php" class="btn btn-primary">Agregar Producto</a>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
