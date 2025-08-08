<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../model/model_usuario.php';

$MU = new Modelo_Usuario();
$usu = htmlspecialchars($_POST['u'], ENT_QUOTES, 'UTF-8');
$con = htmlspecialchars($_POST['c'], ENT_QUOTES, 'UTF-8');
$response = [];

try {
    $consulta = $MU->VerificarUsuario($usu);

    if ($consulta && count($consulta) > 0) {
        $datosUsuario = $consulta[0];

        if (password_verify($con, $datosUsuario->usu_contra)) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // --- SESIÓN COMPLETA: Ahora guardamos todos los datos ---
            $_SESSION['id_usuario'] = $datosUsuario->usu_id;
            $_SESSION['nombre_usuario'] = $datosUsuario->usu_nombre;
            $_SESSION['area_id'] = $datosUsuario->area_id; // ¡Dato clave añadido!
            $_SESSION['rol'] = $datosUsuario->rol;         // ¡Dato clave añadido!
            $_SESSION['autenticado'] = true;

            $response['status'] = 'success';
            $response['redirect'] = BASE_URL . 'view/index.php';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Usuario o contraseña incorrectos.';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Usuario o contraseña incorrectos.';
    }

} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Ocurrió un error inesperado en el servidor.';
    error_log($e->getMessage());
}

header('Content-Type: application/json');
echo json_encode($response);
?>