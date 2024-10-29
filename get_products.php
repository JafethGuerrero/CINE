<?php
include 'conexion.php'; // Incluir el archivo de conexión

$category = isset($_GET['category']) ? $_GET['category'] : '';
$products = [];

// Consulta para obtener los productos de la categoría seleccionada
if ($category) {
    $sql = "SELECT nombre, precio FROM almacen WHERE categoria = ?";
    $params = [$category];
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt) {
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $products[] = $row;
        }
    }
}

// Generar las filas de productos
foreach ($products as $product) {
    echo '<tr>';
    echo '<td>' . htmlspecialchars($product['nombre']) . '</td>';
    echo '<td>$' . number_format($product['precio'], 2) . '</td>';
    echo '<td><button class="btn btn-primary add-to-order" data-name="' . htmlspecialchars($product['nombre']) . '" data-price="' . $product['precio'] . '">Agregar</button></td>';
    echo '</tr>';
}
?>
