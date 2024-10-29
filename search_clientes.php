<?php
include 'conexion.php'; // Incluir el archivo de conexión

// Verificar si se ha enviado un término de búsqueda
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Modificar la consulta SQL para incluir la búsqueda
$sql = "SELECT * FROM clientes";
if (!empty($searchTerm)) {
    $sql .= " WHERE nombre LIKE ? OR correo_electronico LIKE ? OR celular LIKE ? OR cuenta_bancaria LIKE ?";
}

// Preparar y ejecutar la consulta
$params = [];
if (!empty($searchTerm)) {
    $params = ["%$searchTerm%", "%$searchTerm%", "%$searchTerm%", "%$searchTerm%"];
}
$stmt = sqlsrv_query($conn, $sql, $params);
?>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Correo Electrónico</th>
            <th>Celular</th>
            <th>Cuenta Bancaria</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id_cliente']); ?></td>
                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                <td><?php echo htmlspecialchars($row['correo_electronico']); ?></td>
                <td><?php echo htmlspecialchars($row['celular']); ?></td>
                <td><?php echo htmlspecialchars($row['cuenta_bancaria']); ?></td>
                <td class="text-center">
                    <a href="./edit_cliente.php?id=<?php echo $row['id_cliente']; ?>" class="btn btn-default" title="Modificar">
                        <i class="fa fa-pencil"></i>
                    </a>
                    <a href="./eliminar_cliente.php?id=<?php echo $row['id_cliente']; ?>" class="btn btn-default" title="Eliminar">
                        <i class="fa fa-remove"></i>
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
