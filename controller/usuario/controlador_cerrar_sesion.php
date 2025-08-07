<?php
// 1. Reanudamos la sesión existente.
session_start();

// 2. Eliminamos todas las variables de la sesión.
$_SESSION = array();

// 3. Destruimos la sesión por completo.
session_destroy();

// 4. Redirigimos al usuario a la página de login.
//    Asegúrate que la ruta a tu login sea correcta.
header('Location: ../../index.php');

// 5. Nos aseguramos de que el script se detenga después de la redirección.
exit();
?>