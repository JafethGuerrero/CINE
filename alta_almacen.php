<?php
include("header.php");
include("conexion.php");

// Obtener todos los productos
$queryProductos = "SELECT id_producto, nombre_producto FROM Productos";
$resultProductos = sqlsrv_query($conn, $queryProductos);

// Obtener todos los proveedores
$queryProveedores = "SELECT id_proveedor, nombre_proveedor FROM Proveedor";
$resultProveedores = sqlsrv_query($conn, $queryProveedores);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Almacén</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Registrar Nuevo Almacén</h2>
    <form method="post" action="alta_almacen_logic.php">
        <div class="mb-3">
            <label for="cantidad" class="form-label">Cantidad (*)</label>
            <input class="form-control" type="number" name="cantidad" required>
        </div>

        <div class="mb-3">
            <label for="tipo_almacenamiento" class="form-label">Tipo Almacenamiento (*)</label>
            <input class="form-control" type="text" name="tipo_almacenamiento" required>
        </div>

        <div class="mb-3">
            <label for="fecha_reabastecimiento" class="form-label">Fecha Reabastecimiento (*)</label>
            <input class="form-control" type="text" name="fecha_reabastecimiento" required>
        </div>

        <div class="mb-3">
            <label for="id_producto" class="form-label">Seleccionar Producto (*)</label>
            <select class="form-select" name="id_producto" required>
                <option value="">Seleccione un producto</option>
                <?php while ($producto = sqlsrv_fetch_array($resultProductos)): ?>
                    <option value="<?php echo htmlspecialchars($producto['id_producto']); ?>">
                        <?php echo htmlspecialchars($producto['nombre_producto']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="id_proveedor" class="form-label">Seleccionar Proveedor (*)</label>
            <select class="form-select" name="id_proveedor" required>
                <option value="">Seleccione un proveedor</option>
                <?php while ($proveedor = sqlsrv_fetch_array($resultProveedores)): ?>
                    <option value="<?php echo htmlspecialchars($proveedor['id_proveedor']); ?>">
                        <?php echo htmlspecialchars($proveedor['nombre_proveedor']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Registrar Almacén</button>
        <a href="almacen.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
