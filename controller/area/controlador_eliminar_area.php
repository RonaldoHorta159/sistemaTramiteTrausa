<?php
require_once '../../model/model_area.php';

if (isset($_POST['id'])) {
    $id = htmlspecialchars($_POST['id'], ENT_QUOTES, 'UTF-8');

    $MA = new Modelo_Area();
    $resultado = $MA->EliminarArea($id);

    echo $resultado;
} else {
    echo "ERROR_POST";
}
?>