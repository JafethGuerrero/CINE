<?php
include 'conexion.php'; // Incluir el archivo de conexión
include 'footer.php'; // Incluir el footer
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartelera de Películas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body{
            background-color: #f8f9fa;
        }
        .search-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }        
        .search-container input {
            flex-grow: 1;
            margin-right: 10px;
        }
        .add-button:hover {
            background-color: #218838;
        }
        .action-buttons .btn {
            margin-right: 5px;
        }
        .btn-hover:hover {
            background-color: #f0ad4e;
        }
        .action-buttons a {
            text-decoration: none;
        }
    </style>
</head>
<body>

<?php include 'header.php'; // Incluir el encabezado ?>
<div class="container mt-5">
    <h2 class="text-center mb-4">Cartelera de Películas</h2>

    <!-- Barra de búsqueda -->
    <div class="d-flex justify-content-between align-items-center mb-3">
    <form id="search-form" class="d-flex">
        <input type="text" id="search" class="form-control" placeholder="Buscar película...">
        <button type="button" class="btn btn-primary" disabled>Buscar</button>
    </form>
    <a href="taquilla.php" class="btn btn-info" title="Regresar a la taquilla">
        <i class="fas fa-arrow-left"></i>
    </a>
    <a href="agregar_pelicula.php" class="btn btn-success" title="Agregar Película">
        <i class="fa fa-plus"></i>
        </a>
    
        </div>

    <!-- Contenedor para resultados -->
    <div id="resultados">
        <!-- Aquí se mostrarán los resultados con AJAX -->
    </div>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<script>
$(document).ready(function () {
    // Función para cargar los resultados iniciales
    function cargarPeliculas(query = '', page = 1) {
        $.ajax({
            url: 'search_cartelera.php',
            method: 'POST',
            data: { query: query, page: page },
            success: function (data) {
                $('#resultados').html(data);
            }
        });
    }

    // Cargar resultados iniciales
    cargarPeliculas();

    // Búsqueda dinámica
    $('#search').on('input', function () {
        const query = $(this).val();
        cargarPeliculas(query);
    });

    // Paginación
    $(document).on('click', '.page-link', function (e) {
        e.preventDefault();
        const page = $(this).data('page');
        const query = $('#search').val();
        cargarPeliculas(query, page);
    });
});
</script>
