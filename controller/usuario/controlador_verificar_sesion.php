<?php
// Iniciamos la sesión para poder acceder a las variables de sesión.
session_start();

// Verificamos dos cosas:
// 1. Que la variable de sesión 'autenticado' exista.
// 2. Que su valor sea exactamente true.
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {

    // Si no está autenticado, lo redirigimos a la página de login.
    // Usamos una ruta absoluta para evitar problemas.
    header('Location: ../../index.php');

    // Es importante usar exit() después de una redirección para detener
    // la ejecución del resto del script.
    exit();
}

// Si el script llega hasta aquí, significa que el usuario sí está autenticado
// y puede ver el contenido de la página.
?>