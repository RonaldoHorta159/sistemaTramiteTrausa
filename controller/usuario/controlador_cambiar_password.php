<?php
require_once '../../model/model_usuario.php';

if (isset($_POST['id'], $_POST['newPassword'])) {
    $id = htmlspecialchars($_POST['id'], ENT_QUOTES, 'UTF-8');
    $newPassword = htmlspecialchars($_POST['newPassword'], ENT_QUOTES, 'UTF-8');

    $MU = new Modelo_Usuario();
    echo $MU->CambiarPassword($id, $newPassword);
} else {
    echo "ERROR_POST";
}
?>