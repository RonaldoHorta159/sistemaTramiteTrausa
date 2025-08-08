<?php
require '../../model/model_tramite.php';
$MT = new Modelo_Tramite(); //Instanciamos

// Llamamos a la nueva función del modelo
$consulta = $MT->Listar_Tramite();

// Verificamos si la consulta devolvió datos
if ($consulta) {
    // Si hay datos, los codificamos y los enviamos en el formato que DataTables espera
    echo json_encode(['data' => $consulta]);
} else {
    // Si no hay datos, enviamos una estructura vacía
    echo json_encode(['data' => []]);
}
?>