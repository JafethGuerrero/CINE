<?php
include 'conexion.php'; // Incluir el archivo de conexión
include 'header.php'; // Incluir el encabezado
include 'footer.php'; // Incluir el footer

// Consulta SQL para obtener los datos de la cartelera
$sql = "SELECT id_pelicula, pelicula, fecha_limit FROM Cartelera";
$stmt = sqlsrv_query($conn, $sql);

// Guardamos todos los resultados de la consulta en un array
$peliculas = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $peliculas[] = $row;
}
?>

<div class="container mt-5">
    <h4 class="text-center mb-4">Cartelera de Películas</h4>
    <div class="carousel slide" id="movieCarousel" data-ride="carousel">
        <div class="carousel-inner">
            <?php 
            $activeClass = 'active'; 

            foreach ($peliculas as $row):
                $movieName = $row['pelicula'];
                $imagePath = 'imagenes_peliculas/' . strtolower(str_replace(' ', '_', $movieName)) . '.jpg';
                if (!file_exists($imagePath)) {
                    $imagePath = 'imagenes_peliculas/default.jpg';
                }
            ?>
                <div class="carousel-item <?php echo $activeClass; ?>">
                    <img src="<?php echo $imagePath; ?>" class="d-block w-50 mx-auto img-fluid" style="max-height: 30vh; object-fit: contain;" alt="<?php echo htmlspecialchars($movieName); ?>">
                    <div class="text-center mt-2">
                        <h5><?php echo htmlspecialchars($movieName); ?></h5>
                        <p><strong>Fecha Límite:</strong> <?php echo date('d/m/Y', strtotime($row['fecha_limit']->format('Y-m-d'))); ?></p>
                    </div>
                </div>
            <?php 
                $activeClass = '';
            endforeach;
            ?>
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

    <!-- Tabla de películas alineada debajo del carrusel -->
    <div class="table-responsive mt-4"> <!-- Agregar contenedor de tabla responsive -->
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID Película</th>
                    <th>Pelicula</th>
                    <th>Fecha Límite</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($peliculas as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id_pelicula']); ?></td>
                        <td><?php echo htmlspecialchars($row['pelicula']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($row['fecha_limit']->format('Y-m-d'))); ?></td>
                        <td>
                            <a href="modificar_pelicula.php?id=<?php echo htmlspecialchars($row['id_pelicula']); ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Modificar
                            </a>
                            <a href="eliminar_pelicula.php?id=<?php echo htmlspecialchars($row['id_pelicula']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar esta película?');">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

    <div class="text-center mb-4">
        <!-- Botón para regresar a la taquilla -->
            <a href="taquilla.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Regresar a la Taquilla</a>
    </div>
    <div class="text-center mb-4">
    <a href="agregar_pelicula.php" class="btn btn-success">
        <i class="fas fa-plus-circle"></i> Agregar Película
    </a>
</div>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
