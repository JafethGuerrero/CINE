<?php
include("header.php");
include("conexion.php");

$querySalas = "SELECT * FROM Salas";
$resultSalas = sqlsrv_query($conn, $querySalas);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Salas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Listado de Salas</h2>
    <a href="alta_salas.php" class="btn btn-primary mb-3">Agregar Nueva Sala</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID Sala</th>
                <th>Nombre</th>
                <th>Cantidad de Asientos</th>
                <th>Tipo de Proyección</th>
                <th>Pelicula</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($sala = sqlsrv_fetch_array($resultSalas)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($sala['id_salas']); ?></td>
                    <td><?php echo htmlspecialchars($sala['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($sala['cantidad_asientos']); ?></td>
                    <td><?php echo htmlspecialchars($sala['tipo_proyeccion']); ?></td>
                    <td><?php echo htmlspecialchars($sala['pelicula']); ?></td>
                    <td>
                        <a href="edit_salas.php?id=<?php echo htmlspecialchars($sala['id_salas']); ?>" class="btn btn-warning" title = "Modificar">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <a href="eliminar_salas.php?id=<?php echo htmlspecialchars($sala['id_salas']); ?>" class="btn btn-danger" title = "Eliminar">
                            <i class="fa fa-remove"></i>
                        </a>

                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
