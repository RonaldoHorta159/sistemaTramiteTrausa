<?php
require_once '../../model/model_usuario.php';

$MU = new Modelo_Usuario();
$consulta = $MU->ListarAreasActivas();

echo json_encode($consulta);
?>