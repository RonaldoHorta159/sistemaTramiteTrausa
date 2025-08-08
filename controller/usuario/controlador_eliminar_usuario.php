<?php
require_once '../../model/model_usuario.php';

if (isset($_POST['id'])) {
    $id = htmlspecialchars($_POST['id'], ENT_QUOTES, 'UTF-8');
    $MU = new Modelo_Usuario();
    echo $MU->EliminarUsuario($id);
} else {
    echo "ERROR_POST";
}
?>