<?php
require_once '../../model/model_area.php';

if (isset($_POST['nombreArea'])) {
    $nombreArea = htmlspecialchars($_POST['nombreArea'], ENT_QUOTES, 'UTF-8');
    $nombreArea = mb_strtoupper($nombreArea, 'UTF-8'); // Convertir a mayúsculas
    $MA = new Modelo_Area();
    $resultado = $MA->RegistrarArea($nombreArea);
    echo $resultado;
}
?>