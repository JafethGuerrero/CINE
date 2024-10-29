<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_empleado = $_POST['username'];
    $password = $_POST['password'];

    // Consulta para verificar el id_empleado y la contraseña en la tabla empleados
    $sql = "SELECT puesto, contrasena FROM empleados WHERE id_empleado = ?";
    $params = array($id_empleado);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt && $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        if ($row['contrasena'] === $password) {
            $_SESSION['user_role'] = $row['puesto'];
            switch ($row['puesto']) {
                case 'Taquilla':
                    header("Location: Taquilla.php");
                    break;
                case 'Supervisor de Taquilla':
                    header("Location: supervisor_taquilla.php");
                    break;
                case 'Dulcería':
                    header("Location: Dulceria.php");
                    break;
                case 'Supervisor de Dulcería':
                    header("Location: supervisor_dulceria.php");
                    break;
                case 'Limpieza':
                    header("Location: limpieza.php");
                    break;
                case 'Supervisor de Limpieza':
                    header("Location: supervisor_limpieza.php");
                    break;
                case 'Administrador':
                    header("Location: admin.php");
                    break;
                default:
                    echo "No se encontró la ventana correspondiente.";
            }
            exit();
        } else {
            $error_message = "Contraseña incorrecta.";
        }
    } else {
        $error_message = "Usuario no encontrado.";
    }
}
?>