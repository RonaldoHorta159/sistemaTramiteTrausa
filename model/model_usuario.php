<?php
// Archivo: model/model_usuario.php
require_once 'Database.php';

class UsuarioModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function verificarUsuario($nombre_usuario, $password)
    {
        $sql = "SELECT id, nombre_usuario, password_hash, rol, area_id 
                FROM usuario 
                WHERE nombre_usuario = ? AND estado = 'ACTIVO'";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$nombre_usuario]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($password, $usuario['password_hash'])) {
            unset($usuario['password_hash']);
            return $usuario;
        }

        return false;
    }
}