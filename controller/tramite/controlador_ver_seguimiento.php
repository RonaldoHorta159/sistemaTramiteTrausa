<?php
require_once '../../model/model_tramite.php';

// Verificamos que se haya enviado un ID de documento
if (isset($_POST['id'])) {
    $documentoId = htmlspecialchars($_POST['id'], ENT_QUOTES, 'UTF-8');

    $MT = new Modelo_Tramite();
    $consulta = $MT->VerSeguimiento($documentoId);

    // Devolvemos el historial en formato JSON
    echo json_encode($consulta);
} else {
    // Si no se envió ID, devolvemos un array vacío
    echo json_encode([]);
}
?>