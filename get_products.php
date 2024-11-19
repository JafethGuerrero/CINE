<?php
include 'conexion.php'; // Incluir el archivo de conexión

$category = $_GET['category']; // Obtener la categoría seleccionada

// Consulta para obtener los productos de la categoría con solo el nombre y la cantidad
$sql = "SELECT p.nombre_producto, a.cantidad
        FROM productos p
        JOIN almacen a ON p.id_producto = a.id_producto
        JOIN categorias c ON p.id_categoria = c.id_categoria
        WHERE c.nombre_categoria = ?";
$stmt = sqlsrv_query($conn, $sql, array($category));

if ($stmt) {
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        echo "<tr>
                <td>" . htmlspecialchars($row['nombre_producto']) . "</td>
                <td>" . $row['cantidad'] . "</td>
                <td><button class='btn btn-success add-to-order' data-name='" . htmlspecialchars($row['nombre_producto']) . "'>Agregar</button></td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='3'>No se encontraron productos en esta categoría.</td></tr>";
}
?>
