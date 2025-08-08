<?php
require_once '../../model/model_tramite.php';

$MT = new Modelo_Tramite();
$consulta = $MT->ListarTiposDocumentoActivos();

echo json_encode($consulta);
?>