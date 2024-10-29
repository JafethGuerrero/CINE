<?php
session_start();
include 'footer.php';


// Aquí puedes agregar la lógica de verificación de sesión más adelante
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador - Cine</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons (opcional) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        /* Estilo general de la página */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: linear-gradient(to bottom, #00BFFF 10%, #FFD700 10%, #FF6347 80%, #FF4500 100%);
            transition: background-color 0.3s, color 0.3s;
        }

        /* Barra de Navegación Superior */
        .navbar {
            background-color: #007BFF;
        }
        .navbar-brand, .nav-link {
            color: #FFFFFF !important;
        }
        .nav-link.active {
            background-color: #0056b3;
            border-radius: 5px;
            color: white !important;
        }

        /* Estilos del contenedor de botones */
        .container {
            padding-top: 40px;
            padding-bottom: 40px;
            flex-grow: 1;
        }

        /* Botones con imágenes */
        .btn-area {
            width: 100%;
            height: 200px;
            margin-bottom: 30px;
            position: relative;
            border: none;
            border-radius: 10px;
            transition: transform 0.2s, background-color 0.3s;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: #FFFFFF;
            background-color: #007BFF;
        }
        .btn-area:hover {
            transform: scale(1.05);
            background-color: #0056b3;
        }
        .btn-area img {
            max-width: 80px;
            max-height: 80px;
            object-fit: contain;
            margin-bottom: 10px;
        }
        .btn-area .overlay {
            position: absolute;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            color: #f1f1f1;
            width: 100%;
            text-align: center;
            padding: 10px 0;
        }

        /* Footer */
        .footer {
            background-color: #F1C40F;
            color: #FFFFFF;
            text-align: center;
            padding: 10px 0;
        }

    </style>
</head>
<body>

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
                        <a class="nav-link" href="logout.php">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <div class="container">
        <div class="row">
            <!-- Dulcería -->
            <div class="col-md-4">
                <a href="dulceria.php" class="btn btn-area">
                    <img src="assets/images/dulceria.png" alt="Dulcería">
                    <div class="overlay">Dulcería</div>
                </a>
            </div>
            <!-- Taquilla -->
            <div class="col-md-4">
                <a href="taquilla.php" class="btn btn-area">
                    <img src="assets/images/taquilla.png" alt="Taquilla">
                    <div class="overlay">Taquilla</div>
                </a>
            </div>
            <!-- Almacén -->
            <div class="col-md-4">
                <a href="almacen.php" class="btn btn-area">
                    <img src="assets/images/almacen.png" alt="Almacén">
                    <div class="overlay">Almacén</div>
                </a>
            </div>
        </div>
        <div class="row">
            <!-- Limpieza -->
            <div class="col-md-4">
                <a href="limpieza.php" class="btn btn-area">
                    <img src="assets/images/limpieza.png" alt="Limpieza">
                    <div class="overlay">Limpieza</div>
                </a>
            </div>
            <!-- Salas -->
            <div class="col-md-4">
                <a href="salas.php" class="btn btn-area">
                    <img src="assets/images/salas.png" alt="Salas">
                    <div class="overlay">Salas</div>
                </a>
            </div>
            <!-- Clientes -->
            <div class="col-md-4">
                <a href="Clientes.php" class="btn btn-area">
                    <img src="assets/images/Clientes.png" alt="Clientes">
                    <div class="overlay">Clientes</div>
                </a>
            </div>
            <!-- Empleados -->
            <div class="col-md-4">
                <a href="Empleados.php" class="btn btn-area">
                    <img src="assets/images/Empleados.png" alt="Empleados">
                    <div class="overlay">Empleados</div>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
