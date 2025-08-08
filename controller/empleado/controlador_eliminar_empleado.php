<?php
require_once '../../model/model_empleado.php';

if (isset($_POST['id'])) {
    $id = htmlspecialchars($_POST['id'], ENT_QUOTES, 'UTF-8');

    $ME = new Modelo_Empleado();
    $resultado = $ME->EliminarEmpleado($id);

    echo $resultado;
} else {
    echo "ERROR_POST";
}
?>