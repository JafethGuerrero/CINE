<?php
include 'conexion.php'; // Incluir el archivo de conexión
include 'header.php'; // Incluir el encabezado
include 'footer.php'; // Incluir el footer

// Consulta para obtener las categorías de la tabla 'Categorias'
$sql = "SELECT nombre_categoria FROM Categorias";
$stmt = sqlsrv_query($conn, $sql);

$categories = [];
if ($stmt) {
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $categories[] = $row['nombre_categoria'];
    }
}

if($conn === false){
    die('No pudo conectarse con el servidor SQL');
}

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$cutomerData = null;

if($searchTerm){
    $sql = "SELECT id_cliente, nombre FROM clientes WHERE nombre LIKE ?";
    $params = ["%$searchTerm%"];
    $stmt = sqlsrv_query($conn, $sql, $params);
    if($stmt === false){
        die('No pudo conectarse con el servidor SQL');
    }
    if(sqlsrv_has_rows($stmt)){
        $cutomerData = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }
}

$sql = "SELECT nombre_categoria FROM Categorias";
$stmt = sqlsrv_query($conn, $sql);
if($stmt === false){
    die(print_r(sqlsrv_errors(), true));
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dulcería - Cine</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">Dulcería</h2>

    <!-- Barra de búsqueda de clientes -->
    <div class="d-flex justify-content-center">
        <input type="text" id="search-client" class="form-control" placeholder="Buscar cliente..." autocomplete="off">
        <button type="submit" class="btn btn-primary">Buscar</button>
        <button type="reset" class="btn btn-danger">Limpiar</button>
    </div>

    <!-- Resultados de búsqueda de clientes -->
    <div id="customer-info" class="mt-4">
        <?php if ($cutomerData): ?>
            <p><strong>ID:</strong> <?php echo $cutomerData['id_cliente']; ?></p>
            <p><strong>Nombre:</strong> <?php echo $cutomerData['nombre']; ?></p>
            <p><strong>Correo Electrónico:</strong> <?php echo $cutomerData['correo']; ?></p>
            <p><strong>Teléfono:</strong> <?php echo $cutomerData['telefono']; ?></p>
        <?php else: ?>
            <?php if($searchTerm): ?>
                <p class="text-danger">Cliente no encontrado. Por favor, verifica la información.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
           
    <!-- Sección de categorías de productos -->
    <div class="d-flex justify-content-center flex-wrap mt-4">
        <?php if ($categories): ?>
            <?php foreach ($categories as $category): ?>
                <button class="btn btn-info m-2 category-button btn-lg" data-category="<?php echo htmlspecialchars($category); ?>">
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
                    <th>Cantidad en Almacén</th>
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

<script>
    $(document).ready(function() {
        // Búsqueda de cliente con AJAX
        $('#search-client').on('keyup', function() {
            let searchValue = $(this).val();
            if (searchValue.length > 0) {
                $.ajax({
                    url: '',
                    type: 'GET',
                    data: { search: searchValue },
                    success: function(response) {
                        $('#search-results').html(response);
                    }
                });
            } else {
                $('#search-results').empty();
            }
        });

        // Seleccionar un cliente de los resultados de búsqueda
        $(document).on('click', '.cliente-item', function() {
            const clientId = $(this).data('id');
            const clientName = $(this).data('nombre');
            $('#search-results').empty();
            $('#search-client').val(clientName); // Mostrar el nombre del cliente en el input
            window.clientId = clientId; // Guardar el ID del cliente seleccionado
            $('#product-list').removeClass('d-none');
        });

        // Manejar la selección de categoría
        $('.category-button').on('click', function() {
            const category = $(this).data('category');
            $('#product-list').removeClass('d-none');

            // Cargar productos de la categoría seleccionada
            $.ajax({
                url: 'get_products.php',
                type: 'GET',
                data: { category: category },
                success: function(data) {
                    $('#products-body').html(data);
                }
            });
        });

        // Manejar la adición de productos al pedido
        $(document).on('click', '.add-to-order', function() {
            if (!window.clientId) {
                alert('Debe seleccionar un cliente antes de agregar productos.');
                return;
            }

            const productName = $(this).data('name');
            const productPrice = $(this).data('price');

            // Agregar el producto al resumen del pedido
            $('#order-list').append(`<li>${productName} - $${productPrice.toFixed(2)}</li>`);
            $('#order-info').removeClass('d-none');
        });

        // Manejar la confirmación del pedido
        $('#confirm-order').on('click', function() {
            if (!window.clientId) {
                alert('Debe seleccionar un cliente para confirmar el pedido.');
                return;
            }

            alert('Pedido confirmado. ¡Gracias por su compra!');
            $('#order-list').empty();
            $('#order-info').addClass('d-none');
        });
    });
</script>

<style>
    /* Estilo de los botones de categoría */
    .category-button {
        width: 200px;
        height: 80px;
        font-size: 18px;
        margin-bottom: 30px;
        position: relative;
        border-radius: 10px;
        transition: transform 0.3s ease, background-color 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #FFFFFF;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .category-button:hover {
        transform: scale(1.1);
        background-color: #007bff;
    }

    .category-button:active {
        transform: scale(0.95);
        background-color: #0056b3;
    }

    /* Estilo de los resultados de búsqueda de clientes */
    #search-results {
        max-height: 200px;
        overflow-y: auto;
    }

    .cliente-item {
        cursor: pointer;
    }

    .cliente-item:hover {
        background-color: #f0f0f0;
    }
</style>

</body>
</html>
