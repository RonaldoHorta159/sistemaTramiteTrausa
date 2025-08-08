<?php
// Incluimos la clase de conexión que ya tienes
require_once __DIR__ . '/Database.php';

class Modelo_Area
{

    /**
     * Función para obtener todas las áreas de la base de datos.
     */
    public function ListarAreas()
    {
        // Obtenemos la conexión PDO usando tu clase Database
        $pdo = Database::getInstance()->getConnection();

        // Consulta SQL para seleccionar los campos de la tabla 'area'
        $sql = "SELECT
                    id,
                    nombre,
                    fecha_registro,
                    estado
                FROM
                    area";

        try {
            // Preparamos y ejecutamos la consulta
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            // Devolvemos todos los resultados
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            // En caso de error, lo registramos y devolvemos un array vacío
            error_log("Error en ListarAreas: " . $e->getMessage());
            return [];
        }
    }

    public function RegistrarArea($nombre)
    {
        $pdo = Database::getInstance()->getConnection();
        $sql_verificar = "SELECT COUNT(*) FROM area WHERE nombre = ?";
        $stmt_verificar = $pdo->prepare($sql_verificar);
        $stmt_verificar->execute([$nombre]);
        if ($stmt_verificar->fetchColumn() > 0) {
            return "EXISTE";
        }
        $sql_insertar = "INSERT INTO area (nombre) VALUES (?)";
        $stmt_insertar = $pdo->prepare($sql_insertar);
        if ($stmt_insertar->execute([$nombre])) {
            return "OK";
        }
        return "ERROR";
    }

    // Dentro de la clase Modelo_Area
    public function EditarArea($id, $nombre)
    {
        $pdo = Database::getInstance()->getConnection();

        // 1. Verificar si el nuevo nombre ya existe en OTRO registro
        $sql_verificar = "SELECT COUNT(*) FROM area WHERE nombre = ? AND id != ?";
        $stmt_verificar = $pdo->prepare($sql_verificar);
        $stmt_verificar->execute([$nombre, $id]);
        if ($stmt_verificar->fetchColumn() > 0) {
            return "EXISTE"; // El nuevo nombre ya está en uso
        }

        // 2. Si no existe, proceder con la actualización
        $sql_actualizar = "UPDATE area SET nombre = ? WHERE id = ?";
        try {
            $stmt_actualizar = $pdo->prepare($sql_actualizar);
            if ($stmt_actualizar->execute([$nombre, $id])) {
                return "OK"; // Éxito
            } else {
                return "ERROR"; // Fallo
            }
        } catch (PDOException $e) {
            error_log("Error en EditarArea: " . $e->getMessage());
            return "ERROR_EXCEPTION";
        }
    }
    // Dentro de la clase Modelo_Area
    public function EliminarArea($id)
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = "DELETE FROM area WHERE id = ?";

        try {
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$id])) {
                return "OK"; // Éxito
            } else {
                return "ERROR"; // Fallo
            }
        } catch (PDOException $e) {
            error_log("Error en EliminarArea: " . $e->getMessage());
            return "ERROR_EXCEPTION";
        }
    }
}
?>