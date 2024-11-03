<?php
include 'conexion.php'; // Incluir el archivo de conexión
include 'footer.php'; // Incluir el footer
include 'headeralm.php'; // Incluir el encabezado

// Verificar si se ha enviado un término de búsqueda
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Modificar la consulta SQL para incluir la búsqueda
$sql = "SELECT * FROM Proveedor";
if (!empty($searchTerm)) {
    $sql .= " WHERE nombre_proveedor LIKE ? OR contacto LIKE ?";
}

// Preparar y ejecutar la consulta
$params = [];
if (!empty($searchTerm)) {
    $params = ["%$searchTerm%", "%$searchTerm%"];
}
$stmt = sqlsrv_query($conn, $sql, $params);
?>

<div class="container mt-5">
    <h2 class="text-center">Lista de Proveedores</h2>

    <div class="mb-3 text-center">
        <a href="almacen.php" class="btn btn-info btn-animate">Ver Almacen</a>
        <a href="productos.php" class="btn btn-warning btn-animate">Ver Productos</a>
    </div>
    
    <!-- Formulario de búsqueda -->
    <form id="search-form" class="mb-4">
        <div class="input-group">
            <input type="text" id="search" name="search" class="form-control" placeholder="Buscar proveedores..." value="<?php echo htmlspecialchars($searchTerm); ?>">
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
                    <th>Nombre Proveedor</th>
                    <th>Contacto</th>
                    <th>Teléfono</th>
                    <th>RFC</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id_proveedor']); ?></td>
                        <td><?php echo htmlspecialchars($row['nombre_proveedor']); ?></td>
                        <td><?php echo htmlspecialchars($row['contacto']); ?></td>
                        <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                        <td><?php echo htmlspecialchars($row['RFC']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td class="text-center">
                            <a href="edit_proveedor.php?id=<?php echo $row['id_proveedor']; ?>" class="btn btn-default" title="Modificar">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <a href="eliminar_proveedor.php?id=<?php echo $row['id_proveedor']; ?>" class="btn btn-default" title="Eliminar">
                                <i class="fa fa-remove"></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <a href="alta_proveedor.php" class="btn btn-primary">Agregar Proveedor</a>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
