<?php
require_once '../../model/model_tipo_documento.php';
if (isset($_POST['nombre'])) {
    $nombre = htmlspecialchars($_POST['nombre'], ENT_QUOTES, 'UTF-8');
    $MTD = new Modelo_Tipo_Documento();
    echo $MTD->RegistrarTipoDocumento($nombre);
}
?>