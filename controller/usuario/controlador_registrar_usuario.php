<?php
require_once '../../model/model_usuario.php';

if (isset($_POST['empleado'], $_POST['area'], $_POST['usuario'], $_POST['password'], $_POST['rol'])) {

    $empleadoId = htmlspecialchars($_POST['empleado'], ENT_QUOTES, 'UTF-8');
    $areaId = htmlspecialchars($_POST['area'], ENT_QUOTES, 'UTF-8');
    $nombreUsuario = htmlspecialchars($_POST['usuario'], ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
    $rol = htmlspecialchars($_POST['rol'], ENT_QUOTES, 'UTF-8');

    $MU = new Modelo_Usuario();
    $resultado = $MU->RegistrarUsuario($empleadoId, $areaId, $nombreUsuario, $password, $rol);

    echo $resultado; // Devuelve "OK", "EXISTE_USUARIO", etc.
} else {
    echo "ERROR_POST";
}
?>