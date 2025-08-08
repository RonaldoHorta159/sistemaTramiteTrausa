<?php

echo "<pre>"; // La etiqueta <pre> hace que el resultado sea más fácil de leer.

// 1. Incluimos los dos archivos que necesitamos.
require_once 'model/Database.php';
require_once 'model/model_tramite.php';

echo "Archivos incluidos correctamente.\n\n";

// 2. Creamos una instancia del modelo.
$MT = new Modelo_Tramite();
echo "Instancia del modelo creada.\n\n";

// 3. Llamamos a la función para listar los trámites.
$consulta = $MT->ListarTramites();
echo "Función ListarTramites ejecutada. Resultado:\n\n";

// 4. Mostramos en pantalla lo que la función devolvió.
var_dump($consulta);

echo "</pre>";

?>