<?php
require_once '../../model/model_tramite.php';

$MT = new Modelo_Tramite();
$consulta = $MT->ListarAreasActivas();

echo json_encode($consulta);
?>