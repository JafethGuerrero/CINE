<?php
include 'conexion.php'; // Incluir la conexión
include 'header.php'; // Incluir el header
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Empleados</title>
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
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Lista de Empleados</h2>
        
        <!-- Barra de herramientas -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <!-- Formulario de búsqueda -->
            <form id="search-form" class="d-flex">
                <input type="text" id="search" name="search" class="form-control me-2" placeholder="Buscar empleados...">
                <button type="button" class="btn btn-primary me-2" disabled>Buscar</button>
            </form>

            <!-- Botón de agregar -->
            <a href="alta_empleado.php" class="btn btn-success" title="Agregar Empleado">
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
            // Función para cargar datos
            function loadData(page = 1, searchTerm = '') {
                $.ajax({
                    url: 'search_empleados.php', // Archivo PHP que procesa los datos
                    method: 'GET',
                    data: { page: page, search: searchTerm },
                    success: function (data) {
                        $('#results').html(data);
                    }
                });
            }

            // Cargar datos al inicio
            loadData();

            // Evento para búsqueda mientras se escribe
            $('#search').on('input', function () {
                const searchTerm = $(this).val();
                loadData(1, searchTerm);
            });

            // Delegar clic en paginación
            $(document).on('click', '.pagination a', function (e) {
                e.preventDefault();
                const page = $(this).attr('data-page');
                const searchTerm = $('#search').val();
                loadData(page, searchTerm);
            });
        });
    </script>
</body>
</html>
