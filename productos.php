<?php
include 'conexion.php'; // Incluir el archivo de conexión
include 'headeralm.php'; // Incluir el encabezado
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Productos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .table-container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        footer {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    text-align: center;
    padding: 10px 0;
    background-color: #343a40;
    color: white;
}
.btn-animate {
    display: inline-flex;
    align-items: center;
    padding: 10px 20px;
    font-size: 16px;
}

.btn-animate i {
    margin-right: 8px;
    font-size: 18px;
}

    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Lista de Productos</h2>

        <div class="mb-3 text-center">
        <a href="almacen.php" class="btn btn-info btn-animate">
    <i class="fa fa-archive"></i> Ver Almacén
</a>
<a href="proveedores.php" class="btn btn-warning btn-animate">
    <i class="fa fa-truck"></i> Ver Proveedores
</a>


        <!-- Barra de herramientas -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <!-- Formulario de búsqueda -->
            <form id="search-form" class="d-flex">
                <input type="text" id="search" name="search" class="form-control me-2" placeholder="Buscar productos...">
                <button type="button" class="btn btn-primary me-2" disabled>Buscar</button>
            </form>

            <!-- Botón de agregar -->
            <a href="alta_producto.php" class="btn btn-success" title="Agregar Producto">
                <i class="fa fa-plus"></i>
            </a>
        </div>

        <!-- Contenedor para los resultados -->
        <div id="results" class="table-container">
            <!-- Los resultados dinámicos se cargarán aquí -->
        </div>
    </div>

    <?php include 'footer.php'; // Incluir el footer ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Función para cargar los productos con búsqueda y paginación
            function loadData(page = 1, searchTerm = '') {
                $.ajax({
                    url: 'search_productos.php', // Archivo PHP que procesa los datos
                    method: 'GET',
                    data: { page: page, search: searchTerm },
                    success: function (data) {
                        $('#results').html(data);
                    }
                });
            }

            // Cargar los datos al inicio
            loadData();

            // Evento para búsqueda mientras se escribe
            $('#search').on('input', function () {
                const searchTerm = $(this).val();
                loadData(1, searchTerm); // Siempre cargar desde la página 1 al hacer búsqueda
            });

            // Delegar clic en paginación
            $(document).on('click', '.pagination a', function (e) {
                e.preventDefault();
                const page = $(this).attr('data-page');
                const searchTerm = $('#search').val();
                loadData(page, searchTerm); // Cargar la página específica con la búsqueda actual
            });
        });
    </script>
</body>
</html>
