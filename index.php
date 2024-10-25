<?php
session_start();

// Verifica si el usuario está logueado como administrador
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    header("Location: admin.php"); // Redirige al panel del admin si está logueado
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido al Cine</title>
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

        /* Estilo del contenedor principal */
        .main-container {
            text-align: center;
            color: #FFF; /* Color del texto */
        }

        /* Estilo de la imagen */
        .logo {
            width: 150px; /* Ajusta el tamaño según sea necesario */
            margin-bottom: 20px;
        }

        /* Estilo del botón Entrar */
        .btn-entrar {
            padding: 10px 20px;
            background-color: #FFD700;
            color: #000;
            font-size: 18px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
            transition: background-color 0.3s;
            text-decoration: none; /* Quitar subrayado del enlace */
        }

        /* Efecto hover para el botón */
        .btn-entrar:hover {
            background-color: #FFC107;
        }
    </style>
</head>
<body>

    <div class="main-container">
        <img src="imagen_sinsajo.png" alt="Logo Sinsajo" class="logo"> <!-- Asegúrate de que la imagen esté en la misma carpeta -->
        <h1>Bienvenido al Cine</h1>
        <p>¡Disfruta de la mejor experiencia cinematográfica!</p>
        <a href="login.php">
            <button class="btn-entrar">Entrar</button>
        </a>
    </div>

</body>
<footer style="text-align:center;">© Cine - <?php echo date("Y");?></footer> 
</html>
