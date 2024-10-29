<?php
session_start();
include 'conexion.php';
include 'footer.php';
include 'l_login.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión - Cine</title>
    <style>
        /* Estilo general de la página */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(to bottom, #00BFFF 10%, #FFD700 10%, #FF6347 80%, #FF4500 100%);
        }

        /* Contenedor de la tarjeta de inicio de sesión */
        .login-container {
            background: rgba(255, 255, 255, 0.85);
            padding: 30px;
            border-radius: 15px;
            width: 300px;
            text-align: center;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
        }

        /* Estilo del título */
        .login-container h2 {
            margin: 0 0 20px 0;
            font-size: 24px;
            color: #000;
        }

        /* Estilo de los campos de entrada */
        .form-input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 2px solid #000;
            border-radius: 5px;
            background-color: #FFF;
            color: #000;
            text-align: center;
            box-sizing: border-box;
        }

        /* Estilo del botón Ingresar */
        .btn-ingresar, .btn-cancelar {
            width: 100%;
            padding: 10px;
            background-color: #FFFFFF;
            border: 2px solid #000;
            color: #000;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            box-sizing: border-box;
        }

        /* Estilo del botón Cancelar */
        .btn-cancelar {
            margin-top: 10px;
        }

        /* Efecto hover para los botones */
        .btn-ingresar:hover, .btn-cancelar:hover {
            background-color: #f0f0f0;
        }

        /* Estilo para mostrar mensajes de error */
        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>INICIO DE SESIÓN</h2>
        <form action="" method="POST">
            <input type="text" class="form-input" id="username" name="username" placeholder="Ingresa tu ID de empleado" required>
            <input type="password" class="form-input" id="password" name="password" placeholder="Ingresa tu contraseña" required>
            <button type="submit" class="btn-ingresar">Ingresar</button>
            <button type="button" class="btn-cancelar" onclick="window.location.href='index.php';">Cancelar</button>
            <?php if (isset($error_message)): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>
        </form>
    </div>

</body>
</html>
