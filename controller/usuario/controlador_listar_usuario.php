<?php
// Incluimos el modelo que acabamos de modificar
require_once '../../model/model_usuario.php';

// Creamos una instancia del modelo
$MU = new Modelo_Usuario();

// Llamamos a la función para obtener los datos
$consulta = $MU->ListarUsuarios();

// Si la consulta tiene datos, los preparamos para DataTables
if ($consulta) {
    // DataTables espera un formato específico: un objeto con una clave "data"
    $response = ['data' => $consulta];
    echo json_encode($response);
} else {
    // Si no hay datos, enviamos un array vacío
    echo json_encode(['data' => []]);
}

?>