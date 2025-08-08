<?php
require_once '../../model/model_area.php';

// Verificamos que se hayan enviado todos los datos necesarios
if (isset($_POST['id'], $_POST['nombreArea'])) {
    $id = htmlspecialchars($_POST['id'], ENT_QUOTES, 'UTF-8');
    $nombreArea = htmlspecialchars($_POST['nombreArea'], ENT_QUOTES, 'UTF-8');

    $MA = new Modelo_Area();
    $resultado = $MA->EditarArea($id, $nombreArea);

    // Devolvemos el resultado
    echo $resultado;
} else {
    echo "ERROR_POST";
}
?>