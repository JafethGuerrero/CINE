<?php
session_start();
include("header.php"); // Verifica que este archivo exista en la ruta correcta
include("conexion.php");
include "footer.php"; // Incluimos el footer

// Consulta para obtener las salas
$sqlSalas = "SELECT id_salas, nombre FROM salas"; 
$resultSalas = sqlsrv_query($conn, $sqlSalas);

// Verificar si la consulta se ejecutó correctamente
if ($resultSalas === false) {
    die("Error en la consulta: " . print_r(sqlsrv_errors(), true));
}

// Consulta para obtener los empleados de limpieza
$sqlEmpleados = "SELECT id_empleado, nombre FROM empleados WHERE puesto = 'Limpieza'";
$resultEmpleados = sqlsrv_query($conn, $sqlEmpleados);

// Verificar si la consulta se ejecutó correctamente
if ($resultEmpleados === false) {
    die("Error en la consulta: " . print_r(sqlsrv_errors(), true));
}
?>

<html>
<head>
    <title>Asignar Limpieza</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row col mt-5">
            <form method="post" class="form-control">
                <fieldset class="form-group">
                    <legend><b>Asignar Limpieza a las Salas</b></legend><hr>

                    <label for="id_salas" class="form-label">Seleccione la Sala (*)</label>
                    <select class="form-select" name="id_salas" required>
                        <option value="">Seleccione una sala</option>
                        <?php 
                        // Mostrar las salas en el dropdown
                        while ($sala = sqlsrv_fetch_array($resultSalas, SQLSRV_FETCH_ASSOC)): ?>
                            <option value="<?php echo htmlspecialchars($sala['id_salas']); ?>">
                                <?php echo htmlspecialchars($sala['nombre']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>

                    <label for="id_empleado" class="form-label">Seleccione el Empleado de Limpieza (*)</label>
                    <select class="form-select" name="id_empleado" required>
                        <option value="">Seleccione un empleado</option>
                        <?php 
                        // Mostrar los empleados en el dropdown
                        while ($empleado = sqlsrv_fetch_array($resultEmpleados, SQLSRV_FETCH_ASSOC)): ?>
                            <option value="<?php echo htmlspecialchars($empleado['id_empleado']); ?>">
                                <?php echo htmlspecialchars($empleado['nombre']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>

                    <label for="estado" class="form-label">Estado de Limpieza (*)</label>
                    <select class="form-select" name="estado" required>
                        <option value="">Seleccione el estado</option>
                        <option value="Limpio">Limpio</option>
                        <option value="Sucio">Sucio</option>
                    </select>

                    <hr>

                    <input class="btn btn-success" type="submit" name="asignar" value="Asignar Limpieza" required>
                    <a href="limpieza.php" class="btn btn-secondary"> Salir</a>
                </fieldset>

                <?php
                if (isset($_POST['asignar'])) {
                    // Obtener los datos del formulario
                    $id_salas = $_POST['id_salas'];
                    $id_empleado = $_POST['id_empleado'];
                    $estado = $_POST['estado'];

                    // Usar el procedimiento almacenado para asignar limpieza
                    $query = "EXEC sp_asignar_limpieza ?, ?, ?";
                    $params = array($id_salas, $id_empleado, $estado);
                    $res = sqlsrv_query($conn, $query, $params);

                    if ($res) {
                        echo "<br><br>
                              <div class='alert alert-success alert-dismissible'>
                                  <a href='./salas.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                  <strong>¡Excelente!</strong> Limpieza asignada exitosamente.
                              </div>";
                    } else {
                        echo "<br><br>
                              <div class='alert alert-danger alert-dismissible'>
                                  <a href='./salas.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                  <strong>Error!</strong> No se pudo asignar la limpieza. " . print_r(sqlsrv_errors(), true) . "
                              </div>";
                    }
                }
                ?>
            </form>
        </div>
    </div>
</body>
</html>
