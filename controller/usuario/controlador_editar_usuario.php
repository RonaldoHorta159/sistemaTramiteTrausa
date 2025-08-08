<?php
require_once '../../model/model_usuario.php';

if (isset($_POST['id'], $_POST['area'], $_POST['rol'], $_POST['estado'])) {

    $id = htmlspecialchars($_POST['id'], ENT_QUOTES, 'UTF-8');
    $areaId = htmlspecialchars($_POST['area'], ENT_QUOTES, 'UTF-8');
    $rol = htmlspecialchars($_POST['rol'], ENT_QUOTES, 'UTF-8');
    $estado = htmlspecialchars($_POST['estado'], ENT_QUOTES, 'UTF-8');

    $MU = new Modelo_Usuario();
    $resultado = $MU->EditarUsuario($id, $areaId, $rol, $estado);

    echo $resultado;
} else {
    echo "ERROR_POST";
}
?>