<?php
session_start();
require_once '../../model/model_tramite.php';

// Verificamos que el usuario esté autenticado y que lleguen todos los datos del formulario.
if (isset($_SESSION['autenticado'], $_POST['id'], $_POST['destino'], $_POST['proveido'])) {

    $documentoId = htmlspecialchars($_POST['id'], ENT_QUOTES, 'UTF-8');
    $areaDestinoId = htmlspecialchars($_POST['destino'], ENT_QUOTES, 'UTF-8');
    $proveido = htmlspecialchars($_POST['proveido'], ENT_QUOTES, 'UTF-8');

    // Obtenemos los datos del usuario que está realizando la acción desde la sesión.
    $usuarioId = $_SESSION['id_usuario'];
    $areaOrigenId = $_SESSION['area_id']; // El área del usuario es el origen del movimiento.

    $MT = new Modelo_Tramite();
    $resultado = $MT->DerivarTramite($documentoId, $areaOrigenId, $areaDestinoId, $proveido, $usuarioId);

    echo $resultado;
} else {
    echo "ERROR_POST";
}
?>