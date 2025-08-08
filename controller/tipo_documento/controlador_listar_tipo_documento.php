<?php
require_once '../../model/model_tipo_documento.php';
$MTD = new Modelo_Tipo_Documento();
$consulta = $MTD->ListarTiposDocumento();

if ($consulta) {
    echo json_encode(['data' => $consulta]);
} else {
    echo json_encode(['data' => []]);
}
?>