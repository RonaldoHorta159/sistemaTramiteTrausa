<?php
require_once '../../model/model_tipo_documento.php';
if (isset($_POST['id'], $_POST['nombre'])) {
    $id = htmlspecialchars($_POST['id'], ENT_QUOTES, 'UTF-8');
    $nombre = htmlspecialchars($_POST['nombre'], ENT_QUOTES, 'UTF-8');
    $MTD = new Modelo_Tipo_Documento();
    echo $MTD->EditarTipoDocumento($id, $nombre);
}
?>