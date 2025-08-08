<?php
require_once '../../model/model_tipo_documento.php';
if (isset($_POST['id'])) {
    $id = htmlspecialchars($_POST['id'], ENT_QUOTES, 'UTF-8');
    $MTD = new Modelo_Tipo_Documento();
    echo $MTD->EliminarTipoDocumento($id);
}
?>