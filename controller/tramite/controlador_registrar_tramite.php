<?php
session_start(); // Necesitamos la sesión para saber qué usuario y área están registrando
require_once '../../model/model_tramite.php';

// Verificamos que los datos básicos lleguen
if (isset($_POST['tipo_doc'], $_POST['nro_doc'], $_POST['asunto'], $_POST['nro_folios']) && isset($_SESSION['id_usuario'])) {

    $nombreArchivo = null; // Inicializamos el nombre del archivo como nulo

    // --- LÓGICA PARA SUBIR EL ARCHIVO PDF ---
    if (isset($_FILES['archivo_pdf']) && $_FILES['archivo_pdf']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['archivo_pdf'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];
        $fileType = $file['type'];

        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['pdf'];

        if (in_array($fileExt, $allowed)) {
            if ($fileSize < 5000000) { // Límite de 5MB
                // Creamos un nombre de archivo único para evitar sobreescrituras
                $nombreArchivo = "tramite_" . uniqid('', true) . "." . $fileExt;
                // OJO: Asegúrate de que esta carpeta exista y tenga permisos de escritura
                $fileDestination = '../../storage/documentos/' . $nombreArchivo;
                move_uploaded_file($fileTmpName, $fileDestination);
            } else {
                echo "ERROR_SIZE"; // Error: archivo muy grande
                exit();
            }
        } else {
            echo "ERROR_TYPE"; // Error: tipo de archivo no permitido
            exit();
        }
    }

    // --- REGISTRO EN LA BASE DE DATOS ---
    $MT = new Modelo_Tramite();
    $resultado = $MT->RegistrarTramite(
        $_POST['tipo_doc'],
        $_POST['nro_doc'],
        $_POST['asunto'],
        $_POST['nro_folios'],
        $_SESSION['id_usuario'],
        $_SESSION['area_id'],
        $_POST['area_destino'], // Pasamos el nuevo parámetro
        null,
        $nombreArchivo
    );

    echo $resultado;

} else {
    echo "ERROR_POST";
}
?>