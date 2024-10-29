<?php include 'footer.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Almacén</title>
    <!-- Bootstrap CSS y estilos adicionales -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Fondo de la página */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #00BFFF 10%, #FFD700 10%, #FF6347 80%, #FF4500 100%);
            color: #333;
        }
        /* Contenedor principal */
        .container {
            margin-top: 30px;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        /* Botones de acción */
        .button-group .btn {
            min-width: 120px;
            font-size: 1rem;
            font-weight: bold;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
    <h2 class="text-center">Gestión de Almacén</h2>

    <!-- Formulario para la gestión de productos -->
    <form id="almacenForm" class="mt-4">
        <div class="form-group">
            <label for="idProducto">ID del Producto:</label>
            <input type="text" id="idProducto" name="idProducto" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="nombre">Nombre del Producto:</label>
            <input type="text" id="nombre" name="nombre" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="cantidad">Cantidad:</label>
            <input type="number" id="cantidad" name="cantidad" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="precio">Precio:</label>
            <input type="text" id="precio" name="precio" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="descripcion">Descripción del Producto:</label>
            <textarea id="descripcion" name="descripcion" rows="4" class="form-control" required></textarea>
        </div>

        <!-- Botones de acción -->
        <div class="button-group d-flex justify-content-between mt-3">
            <button type="button" class="btn btn-success" onclick="agregarProducto()">Agregar</button>
            <button type="button" class="btn btn-warning" onclick="modificarProducto()">Modificar</button>
            <button type="button" class="btn btn-danger" onclick="eliminarProducto()">Eliminar</button>
            <button type="button" class="btn btn-info" onclick="buscarProducto()">Buscar</button>
            <button type="button" class="btn btn-primary" onclick="guardarCambios()">Guardar</button>
            <button type="button" class="btn btn-secondary" onclick="cancelarAccion()">Cancelar</button>
        </div>
    </form>
</div>

<script>
    function agregarProducto() {
        alert('Producto agregado.');
    }
    function modificarProducto() {
        alert('Producto modificado.');
    }
    function eliminarProducto() {
        alert('Producto eliminado.');
    }
    function buscarProducto() {
        alert('Producto encontrado.');
    }
    function guardarCambios() {
        alert('Cambios guardados.');
    }
    function cancelarAccion() {
        document.getElementById('almacenForm').reset();
    }
</script>

</body>
</html>
