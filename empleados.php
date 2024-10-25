<?php
include 'conexion.php'; // Incluir el archivo de conexión
include 'header.php'; // Incluir el encabezado

// Consulta para obtener los empleados
$sql = "SELECT * FROM empleados"; // Cambia esto según tu tabla
$stmt = sqlsrv_query($conn, $sql);
?>

<div class="container mt-5">
    <h2 class="text-center">Lista de Empleados</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Puesto</th>
                <th>Contraseña</th>
                <th>Fecha de Contratación</th>
                <th>Fecha Baja</th>
                <th>Salario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
                <tr>
                    <td><?php echo $row['id_empleado']; ?></td>
                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($row['puesto']); ?></td>
                    <td><?php echo isset($row['contraseña']) ? htmlspecialchars($row['contraseña']) : 'No disponible'; ?></td>
                    <td><?php echo $row['fecha_contratacion'] ? $row['fecha_contratacion']->format('Y-m-d') : 'No disponible'; ?></td>
                    <td><?php echo $row['fecha_baja'] ? $row['fecha_baja']->format('Y-m-d') : 'No disponible'; ?></td>
                    <td><?php echo $row['salario']; ?></td>
                    <td class="text-center">
                        <!-- Enlaces para modificar y eliminar con el id_empleado -->
                        <a href="edit_empleado.php?id=<?php echo $row['id_empleado']; ?>" class="btn btn-default" title="Modificar">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <a href="eliminar_empleados.php?id=<?php echo $row['id_empleado']; ?>" class="btn btn-default" title="Eliminar">
                            <i class="fa fa-remove"></i>
                        </a>
                    </td> <!-- Aquí se cierra el <td> que estaba faltando -->
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="alta_empleado.php" class="btn btn-primary">Agregar Empleado</a>
</div>
</body>
<footer style="text-align:center;">© Cine - <?php echo date("Y");?></footer> 
</html>

