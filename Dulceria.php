<?php
include 'conexion.php'; // Incluir el archivo de conexión
include 'header.php'; // Incluir el encabezado
include 'footer.php'; // Incluir el footer

// Consulta para obtener las categorías de productos de la tabla 'almacen'
$sql = "SELECT DISTINCT categoria FROM almacen";
$stmt = sqlsrv_query($conn, $sql);

$categories = [];
if ($stmt) {
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $categories[] = $row['categoria'];
    }
}
?>

<div class="container mt-5">
    <h2 class="text-center">Dulcería</h2>

    <!-- Sección de categorías de productos -->
    <h4 class="text-center">Selecciona una Categoría</h4>
    <div class="d-flex justify-content-center flex-wrap">
        <?php if ($categories): ?>
            <?php foreach ($categories as $category): ?>
                <button class="btn btn-warning m-2 category-button" data-category="<?php echo htmlspecialchars($category); ?>">
                    <?php echo htmlspecialchars($category); ?>
                </button>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-danger">No hay categorías disponibles en este momento.</p>
        <?php endif; ?>
    </div>

    <!-- Sección para mostrar productos de la categoría seleccionada -->
    <div id="product-list" class="text-center mt-4 d-none">
        <h4>Productos Disponibles</h4>
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody id="products-body">
                <!-- Los productos se cargarán aquí -->
            </tbody>
        </table>
    </div>

    <!-- Información del pedido -->
    <div id="order-info" class="text-center mt-4 d-none">
        <h4>Resumen de Pedido</h4>
        <ul id="order-list"></ul>
        <button id="confirm-order" class="btn btn-success">Confirmar Pedido</button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        // Manejar la selección de categoría
        $('.category-button').on('click', function() {
            const category = $(this).data('category');
            $('#product-list').removeClass('d-none');

            // Cargar productos de la categoría seleccionada
            $.ajax({
                url: 'get_products.php', // Archivo PHP que devolverá los productos
                type: 'GET',
                data: { category: category },
                success: function(data) {
                    $('#products-body').html(data);
                }
            });
        });

        // Manejar la adición de productos al pedido
        $(document).on('click', '.add-to-order', function() {
            const productName = $(this).data('name');
            const productPrice = $(this).data('price');

            // Agregar el producto al resumen del pedido
            $('#order-list').append(`<li>${productName} - $${productPrice.toFixed(2)}</li>`);
            $('#order-info').removeClass('d-none');
        });

        // Manejar la confirmación del pedido
        $('#confirm-order').on('click', function() {
            alert('Pedido confirmado. ¡Gracias por su compra!');
            $('#order-list').empty();
            $('#order-info').addClass('d-none');
        });
    });
</script>
</body>
</html>
