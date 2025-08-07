<?php
// Usaremos la nueva y mejorada clase UsuarioModel
require_once '../../model/model_usuario.php';

// Creamos una instancia del modelo.
$usuarioModel = new UsuarioModel();

// Obtenemos los datos del POST. Usamos filter_input para más seguridad.
$usu = filter_input(INPUT_POST, 'u', FILTER_SANITIZE_SPECIAL_CHARS);
$con = filter_input(INPUT_POST, 'c', FILTER_SANITIZE_SPECIAL_CHARS);

// Creamos un array para la respuesta JSON.
$response = [];

try {
    // Llamamos a nuestro método de verificación eficiente.
    $datosUsuario = $usuarioModel->verificarUsuario($usu, $con);

    if ($datosUsuario) {
        // ¡Credenciales correctas! INICIAMOS LA SESIÓN.
        session_start();

        // Guardamos los datos del usuario en la sesión.
        $_SESSION['id_usuario'] = $datosUsuario['id'];
        $_SESSION['nombre_usuario'] = $datosUsuario['nombre_usuario'];
        $_SESSION['rol'] = $datosUsuario['rol'];
        $_SESSION['area_id'] = $datosUsuario['area_id'];
        $_SESSION['autenticado'] = true;

        // Preparamos una respuesta de éxito.
        $response['status'] = 'success';

    } else {
        // Credenciales incorrectas.
        $response['status'] = 'error';
        $response['message'] = 'Usuario o contraseña incorrectos.';
    }

} catch (Exception $e) {
    // Capturamos cualquier error inesperado.
    $response['status'] = 'error';
    $response['message'] = 'Ocurrió un error en el servidor.';
    error_log($e->getMessage()); // Guardamos el error real en los logs del servidor.
}

// Siempre devolvemos una respuesta en formato JSON.
header('Content-Type: application/json');
echo json_encode($response);

?>