<?php
include 'conexion.php'; // Incluir el archivo de conexión
include 'header.php'; // Incluir el encabezado
include 'footer.php'; // Incluir el footer
?>

<div class="container mt-5">
    <h4 class="text-center">Cartelera de Películas</h4>
    <div class="carousel slide" id="movieCarousel" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="ruta/a/tu/imagen1.jpg" class="d-block w-50 mx-auto" style="max-height: 200px; object-fit: cover;" alt="Película 1">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Película 1</h5>
                    <p>Descripción de la película 1.</p>
                    <p><strong>Horario:</strong> 18:00, 21:00</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="ruta/a/tu/imagen2.jpg" class="d-block w-50 mx-auto" style="max-height: 200px; object-fit: cover;" alt="Película 2">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Película 2</h5>
                    <p>Descripción de la película 2.</p>
                    <p><strong>Horario:</strong> 17:30, 20:30</p>
                </div>
            </div>
            <!-- Agrega más elementos de la cartelera según sea necesario -->
        </div>
        <a class="carousel-control-prev" href="#movieCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Anterior</span>
        </a>
        <a class="carousel-control-next" href="#movieCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Siguiente</span>
        </a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
