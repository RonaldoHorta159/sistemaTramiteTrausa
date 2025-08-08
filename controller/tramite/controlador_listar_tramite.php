<?php
session_start();
require_once '../../model/model_tramite.php';

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    echo json_encode(['data' => []]);
    exit();
}

$MT = new Modelo_Tramite();
$consulta = [];

// Lógica de decisión basada en el ROL
if ($_SESSION['rol'] === 'Administrador') {
    // Si es Admin, llamamos a la función sin parámetro (se usará NULL por defecto).
    $consulta = $MT->ListarTramites();
} else {
    // Si es Usuario, pasamos su ID de área para filtrar los resultados.
    $areaId = $_SESSION['area_id'];
    $consulta = $MT->ListarTramites($areaId);
}

// Devolvemos el resultado
if ($consulta) {
    echo json_encode(['data' => $consulta]);
} else {
    echo json_encode(['data' => []]);
}
?>