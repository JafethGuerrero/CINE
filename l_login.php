<?php
//include 'database.php'; // Ajusta la ruta según sea necesario

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_empleado = $_POST['username'];
    $password = $_POST['password'];

    // Consulta para verificar el id_empleado y la contraseña en la tabla empleados usando el SP
    $sql = "{CALL sp_verificar_contrasena_empleado(?, ?)}";
    $params = array($id_empleado, $password);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt) {
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if ($row['is_valid_password'] == 1) {
            // Si la contraseña es correcta, obtenemos el puesto del empleado
            $sql2 = "SELECT puesto FROM empleados WHERE id_empleado = ?";
            $params2 = array($id_empleado);
            $stmt2 = sqlsrv_query($conn, $sql2, $params2);
            if ($stmt2 && $row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
                $_SESSION['user_role'] = $row2['puesto'];
                switch ($row2['puesto']) {
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
                $error_message = "Usuario no encontrado.";
            }
        } else {
            $error_message = "Contraseña incorrecta.";
        }
    } else {
        $error_message = "Error en la consulta de la base de datos.";
    }
}
?>
