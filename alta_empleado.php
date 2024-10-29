<?php
session_start();
include 'footer.php';
include 'conexion.php'; // Asegúrate de que este archivo contenga la lógica de conexión a la base de datos

// Notificación de estado
if (isset($_SESSION['status'])) {
    $statusMessage = $_SESSION['status'];
    unset($_SESSION['status']); // Limpia el mensaje después de mostrarlo
}

// Manejar el envío del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $puesto = $_POST['puesto'];
    $fecha_contratacion = $_POST['fecha_contratacion'];
    $salario = $_POST['salario'];

    // Consulta para insertar los datos en la tabla Empleados
    $sql = "INSERT INTO Empleados (nombre, puesto, fecha_contratacion, salario) VALUES (?, ?, ?, ?)";
    $params = array($nombre, $puesto, $fecha_contratacion, $salario);

    // Ejecutar la consulta
    $stmt = sqlsrv_query($conn, $sql, $params);

    // Verificar si la inserción fue exitosa
    if ($stmt === false) {
        $_SESSION['status'] = "Error en la inserción: " . print_r(sqlsrv_errors(), true);
        header("Location: alta_empleado.php");
        exit();
    } else {
        $_SESSION['status'] = "Empleado agregado exitosamente.";
        header("Location: alta_empleado.php");
        exit();
    }

    // Cerrar la conexión
    sqlsrv_close($conn);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta de Empleados - Administrador Cine</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilo general de la página */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: linear-gradient(to bottom, #00BFFF 10%, #FFD700 10%, #FF6347 80%, #FF4500 100%);
        }
        .navbar {
            background-color: #007BFF;
            width: 100%;
            position: fixed;
            top: 0;
            z-index: 1000;
        }
        .navbar-brand, .nav-link {
            color: #FFFFFF !important;
        }
        .footer {
            background-color: #F1C40F;
            color: #FFFFFF;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        .form-container {
            max-width: 500px;
            margin: 100px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            display: none;
        }
    </style>
</head>
<body>

    <!-- Notificación -->
    <?php if (isset($statusMessage)): ?>
        <div class="notification alert alert-success" role="alert">
            <?php echo $statusMessage; ?>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const notification = document.querySelector('.notification');
                notification.style.display = 'block';
                setTimeout(() => {
                    notification.style.display = 'none';
                }, 5000); // Ocultar después de 5 segundos
            });
        </script>
    <?php endif; ?>

    <!-- Barra de Navegación Superior -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin.php">Administrador Cine</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link active" href="admin.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <div class="container mt-5">
        <h2 class="text-center">Alta de Empleados</h2>
        <div class="form-container">
            <form action="alta_empleado.php" method="POST">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="mb-3">
                    <label for="puesto" class="form-label">Puesto</label>
                    <select class="form-control" id="puesto" name="puesto" required>
                        <option value="" disabled selected>Seleccione un puesto</option>
                        <option value="Limpieza">Limpieza</option>
                        <option value="Taquilla">Taquilla</option>
                        <option value="Dulceria">Dulcería</option>
                        <option value="Supervisor Taquilla">Supervisor Taquilla</option>
                        <option value="Supervisor Dulceria">Supervisor Dulcería</option>
                        <option value="Supervisor Limpieza">Supervisor Limpieza</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="fecha_contratacion" class="form-label">Fecha de Contratación</label>
                    <input type="date" class="form-control" id="fecha_contratacion" name="fecha_contratacion" required>
                </div>
                <div class="mb-3">
                    <label for="salario" class="form-label">Salario</label>
                    <input type="number" step="0.01" class="form-control" id="salario" name="salario" required>
                </div>
                <button type="submit" class="btn btn-primary">Agregar Empleado</button>
            </form>
        </div>
    </div>
</body>
</html>
