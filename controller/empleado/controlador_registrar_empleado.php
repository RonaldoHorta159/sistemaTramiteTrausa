<?php
require_once '../../model/model_empleado.php';

// Verificamos que todos los datos del formulario hayan llegado
if (isset($_POST['dni'], $_POST['nombres'], $_POST['apePaterno'], $_POST['apeMaterno'], $_POST['email'])) {

    // Limpiamos cada dato para seguridad
    $dni = htmlspecialchars($_POST['dni'], ENT_QUOTES, 'UTF-8');
    $nombres = htmlspecialchars($_POST['nombres'], ENT_QUOTES, 'UTF-8');
    $apePaterno = htmlspecialchars($_POST['apePaterno'], ENT_QUOTES, 'UTF-8');
    $apeMaterno = htmlspecialchars($_POST['apeMaterno'], ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    // Los campos no obligatorios se manejan con un operador ternario
    $celular = !empty($_POST['celular']) ? htmlspecialchars($_POST['celular'], ENT_QUOTES, 'UTF-8') : null;
    $fecNacimiento = !empty($_POST['fecNacimiento']) ? htmlspecialchars($_POST['fecNacimiento'], ENT_QUOTES, 'UTF-8') : null;
    $direccion = !empty($_POST['direccion']) ? htmlspecialchars($_POST['direccion'], ENT_QUOTES, 'UTF-8') : null;

    $ME = new Modelo_Empleado();
    $resultado = $ME->RegistrarEmpleado($dni, $nombres, $apePaterno, $apeMaterno, $email, $celular, $fecNacimiento, $direccion);

    // Devolvemos el resultado (OK, EXISTE, ERROR, etc.)
    echo $resultado;
} else {
    echo "ERROR_POST";
}
?>