<?php
session_start();
include 'header.php'; // Incluir el encabezado
include 'footer.php'; // Incluir el footer

// Notificación de estado
if (isset($_SESSION['status'])) {
    $statusMessage = $_SESSION['status']['message'];
    $statusType = $_SESSION['status']['type'];
    unset($_SESSION['status']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta de Almacén</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 20px;
            background-color: #f8f9fa;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-height: 80vh; /* Altura máxima del contenedor */
            overflow-y: auto; /* Permitir desplazamiento */
        }

        .notification {
            margin-bottom: 20px;
        }

        .btn {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <!-- Notificación -->
    <?php if (isset($statusMessage)): ?>
        <div class="notification alert alert-<?php echo $statusType; ?>" role="alert">
            <strong><?php echo ucfirst($statusType); ?>:</strong> <?php echo $statusMessage; ?>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const notification = document.querySelector('.notification');
                notification.style.display = 'block';
                setTimeout(() => {
                    notification.style.display = 'none';
                }, 5000);
            });
        </script>
    <?php endif; ?>

    <!-- Contenido Principal -->
    <div class="container mt-5">
        <h2 class="text-center">Alta de Almacén</h2>
        
        <!-- Formulario para agregar proveedor -->
        <div class="form-container">
            <h4>Agregar Proveedor</h4>
            <form action="alta_proveedor_logic.php" method="POST">
                <div class="mb-3">
                    <label for="nombre_proveedor" class="form-label">Nombre del Proveedor</label>
                    <input type="text" class="form-control" id="nombre_proveedor" name="nombre_proveedor" required>
                </div>
                <div class="mb-3">
                    <label for="contacto" class="form-label">Contacto</label>
                    <input type="text" class="form-control" id="contacto" name="contacto" required>
                </div>
                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" id="telefono" name="telefono" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <button type="submit" class="btn btn-primary">Agregar Proveedor</button>
            </form>
        </div>

        <!-- Formulario para agregar producto -->
        <div class="form-container mt-4">
            <h4>Agregar Producto</h4>
            <form action="alta_producto_logic.php" method="POST">
                <div class="mb-3">
                    <label for="nombre_producto" class="form-label">Nombre del Producto</label>
                    <input type="text" class="form-control" id="nombre_producto" name="nombre_producto" required>
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="precio" class="form-label">Precio</label>
                    <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
                </div>
                <button type="submit" class="btn btn-primary">Agregar Producto</button>
            </form>
        </div>

        <!-- Formulario para agregar al almacén -->
        <div class="form-container mt-4">
            <h4>Agregar a Almacén</h4>
            <form action="alta_almacen_logic.php" method="POST">
                <div class="mb-3">
                    <label for="ubicacion_producto" class="form-label">Ubicación del Producto</label>
                    <input type="text" class="form-control" id="ubicacion_producto" name="ubicacion_producto" required>
                </div>
                <div class="mb-3">
                    <label for="cantidad" class="form-label">Cantidad</label>
                    <input type="number" class="form-control" id="cantidad" name="cantidad" required>
                </div>
                <div class="mb-3">
                    <label for="tipo_almacenamiento" class="form-label">Tipo de Almacenamiento</label>
                    <input type="text" class="form-control" id="tipo_almacenamiento" name="tipo_almacenamiento" required>
                </div>
                <div class="mb-3">
                    <label for="fecha_reabastecimiento" class="form-label">Fecha de Reabastecimiento</label>
                    <input type="date" class="form-control" id="fecha_reabastecimiento" name="fecha_reabastecimiento" required>
                </div>
                <button type="submit" class="btn btn-primary">Agregar al Almacén</button>
            </form>
        </div>

        <a href="admin.php" class="btn btn-secondary mt-4">Regresar</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
