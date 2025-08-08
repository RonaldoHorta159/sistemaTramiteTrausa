<?php
require_once __DIR__ . '/Database.php';

class Modelo_Tipo_Documento
{

    /**
     * Lista todos los tipos de documento.
     */
    public function ListarTiposDocumento()
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = "SELECT id, nombre, estado FROM tipo_documento";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en ListarTiposDocumento: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Registra un nuevo tipo de documento.
     */
    public function RegistrarTipoDocumento($nombre)
    {
        $pdo = Database::getInstance()->getConnection();
        $sql_verificar = "SELECT COUNT(*) FROM tipo_documento WHERE nombre = ?";
        $stmt_verificar = $pdo->prepare($sql_verificar);
        $stmt_verificar->execute([$nombre]);
        if ($stmt_verificar->fetchColumn() > 0) {
            return "EXISTE";
        }

        $sql_insertar = "INSERT INTO tipo_documento (nombre) VALUES (?)";
        $stmt_insertar = $pdo->prepare($sql_insertar);
        if ($stmt_insertar->execute([$nombre])) {
            return "OK";
        }
        return "ERROR";
    }

    /**
     * Edita el nombre de un tipo de documento existente.
     */
    public function EditarTipoDocumento($id, $nombre)
    {
        $pdo = Database::getInstance()->getConnection();
        $sql_verificar = "SELECT COUNT(*) FROM tipo_documento WHERE nombre = ? AND id != ?";
        $stmt_verificar = $pdo->prepare($sql_verificar);
        $stmt_verificar->execute([$nombre, $id]);
        if ($stmt_verificar->fetchColumn() > 0) {
            return "EXISTE";
        }

        $sql_actualizar = "UPDATE tipo_documento SET nombre = ? WHERE id = ?";
        $stmt_actualizar = $pdo->prepare($sql_actualizar);
        if ($stmt_actualizar->execute([$nombre, $id])) {
            return "OK";
        }
        return "ERROR";
    }

    /**
     * Elimina un tipo de documento.
     */
    public function EliminarTipoDocumento($id)
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = "DELETE FROM tipo_documento WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$id])) {
            return "OK";
        }
        return "ERROR";
    }
}
?>