<?php
include 'conexion.php'; // Incluir el archivo de conexión
include 'header.php'; // Incluir el encabezado

// Consulta para obtener los clientes
$sql = "SELECT * FROM clientes"; // Cambia esto según tu tabla
$stmt = sqlsrv_query($conn, $sql);
?>

<div class="container mt-5">
    <h2 class="text-center">Lista de Clientes</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo Electrónico</th>
                <th>Celular</th>
                <th>Cuenta Bancaria</th>
                <th>Acciones</th> <!-- Cambié el nombre de la columna -->
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
                        <!-- Enlaces para modificar y eliminar con el id_cliente -->
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
    <a href="alta_cliente.php" class="btn btn-primary">Agregar Cliente</a>
</div>

<div class="footer">
    <small>© 2024 Cine</small>
</div>

</body>
</html>

