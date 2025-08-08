<?php
require_once '../../model/model_usuario.php';

$MU = new Modelo_Usuario();
$consulta = $MU->ListarEmpleadosSinUsuario();

// Devuelve directamente el resultado en formato JSON
echo json_encode($consulta);
?>