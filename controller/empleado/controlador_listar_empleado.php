<?php
require_once '../../model/model_empleado.php';

$ME = new Modelo_Empleado();
$consulta = $ME->ListarEmpleados();

if ($consulta) {
    // Preparamos la respuesta en el formato que DataTables espera
    echo json_encode(['data' => $consulta]);
} else {
    // Si no hay datos, enviamos un array vacío
    echo json_encode(['data' => []]);
}
?>