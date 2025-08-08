<?php
// Incluimos el modelo que acabamos de crear
require_once '../../model/model_area.php';

// Creamos una instancia del modelo
$MA = new Modelo_Area();

// Llamamos a la función para obtener la lista de áreas
$consulta = $MA->ListarAreas();

// Verificamos si la consulta devolvió datos
if ($consulta) {
    // Preparamos la respuesta en el formato que DataTables espera: { "data": [...] }
    $response = ['data' => $consulta];
    header('Content-Type: application/json'); // Aseguramos que el navegador lo interprete como JSON
    echo json_encode($response);
} else {
    // Si no hay datos, enviamos un array vacío dentro de la estructura "data"
    echo json_encode(['data' => []]);
}
?>