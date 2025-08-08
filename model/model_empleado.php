<?php
require_once __DIR__ . '/Database.php';

class Modelo_Empleado
{

    /**
     * Lista todos los empleados para la tabla principal.
     */
    public function ListarEmpleados()
    {
        $pdo = Database::getInstance()->getConnection();
        // Seleccionamos todos los campos necesarios.
        $sql = "SELECT 
                    id, 
                    dni, 
                    nombres, 
                    apellido_paterno, 
                    apellido_materno, 
                    email, 
                    celular, 
                    estado 
                FROM empleado";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en ListarEmpleados: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Registra un nuevo empleado en la base de datos.
     */
    public function RegistrarEmpleado($dni, $nombres, $apePaterno, $apeMaterno, $email, $celular, $fecNacimiento, $direccion)
    {
        $pdo = Database::getInstance()->getConnection();

        // Verificamos que el DNI o el email no estén ya registrados para evitar duplicados.
        $sql_verificar = "SELECT COUNT(*) FROM empleado WHERE dni = ? OR email = ?";
        $stmt_verificar = $pdo->prepare($sql_verificar);
        $stmt_verificar->execute([$dni, $email]);
        if ($stmt_verificar->fetchColumn() > 0) {
            return "EXISTE"; // Código para indicar que ya existe.
        }

        // Si no existen, procedemos con la inserción.
        $sql_insertar = "INSERT INTO empleado (dni, nombres, apellido_paterno, apellido_materno, email, celular, fecha_nacimiento, direccion) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        try {
            $stmt_insertar = $pdo->prepare($sql_insertar);
            // Ejecutamos la inserción con todos los parámetros.
            if ($stmt_insertar->execute([$dni, $nombres, $apePaterno, $apeMaterno, $email, $celular, $fecNacimiento, $direccion])) {
                return "OK"; // Éxito
            }
            return "ERROR"; // Fallo
        } catch (PDOException $e) {
            error_log("Error en RegistrarEmpleado: " . $e->getMessage());
            return "ERROR_EXCEPTION";
        }
    }

    /**
     * Edita los datos de un empleado existente.
     */
    public function EditarEmpleado($id, $dni, $nombres, $apePaterno, $apeMaterno, $email, $celular, $fecNacimiento, $direccion)
    {
        $pdo = Database::getInstance()->getConnection();

        // Verificamos que DNI o email no existan en OTRO empleado.
        $sql_verificar = "SELECT COUNT(*) FROM empleado WHERE (dni = ? OR email = ?) AND id != ?";
        $stmt_verificar = $pdo->prepare($sql_verificar);
        $stmt_verificar->execute([$dni, $email, $id]);
        if ($stmt_verificar->fetchColumn() > 0) {
            return "EXISTE";
        }

        $sql_actualizar = "UPDATE empleado SET 
                                dni = ?, 
                                nombres = ?, 
                                apellido_paterno = ?, 
                                apellido_materno = ?, 
                                email = ?, 
                                celular = ?, 
                                fecha_nacimiento = ?, 
                                direccion = ? 
                           WHERE id = ?";
        try {
            $stmt_actualizar = $pdo->prepare($sql_actualizar);
            if ($stmt_actualizar->execute([$dni, $nombres, $apePaterno, $apeMaterno, $email, $celular, $fecNacimiento, $direccion, $id])) {
                return "OK";
            }
            return "ERROR";
        } catch (PDOException $e) {
            error_log("Error en EditarEmpleado: " . $e->getMessage());
            return "ERROR_EXCEPTION";
        }
    }

    /**
     * Elimina un empleado de la base de datos.
     * ¡CUIDADO! Esto podría fallar si el empleado ya está asociado a un usuario o a un documento.
     * Una mejor práctica a futuro sería cambiar su estado a 'INACTIVO' en lugar de borrarlo.
     */
    public function EliminarEmpleado($id)
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = "DELETE FROM empleado WHERE id = ?";
        try {
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$id])) {
                return "OK";
            }
            return "ERROR";
        } catch (PDOException $e) {
            // Este error es común si el empleado tiene registros relacionados (FOREIGN KEY constraint).
            error_log("Error en EliminarEmpleado: " . $e->getMessage());
            return "ERROR_FK"; // Devolvemos un código de error específico.
        }
    }
}
?>