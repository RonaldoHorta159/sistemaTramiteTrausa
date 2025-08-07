<?php
require_once __DIR__ . 'model_conexion.php'; // Aseguramos la ruta correcta

class UsuarioModel
{
    private $pdo;

    public function __construct()
    {
        // Ya tenemos nuestra conexión única y eficiente gracias al Singleton.
        $this->pdo = Database::getInstance()->getConnection();
    }

    /**
     * Verifica las credenciales de un usuario.
     * Esta es la versión mejorada del método del video.
     *
     * @param string $nombre_usuario El usuario para verificar.
     * @param string $password La contraseña en texto plano.
     * @return array|false Los datos del usuario si es válido, o false si no lo es.
     */
    public function verificarUsuario($nombre_usuario, $password)
    {
        // 1. Escribimos la consulta SQL directamente. Es más claro y portable.
        //    Buscamos al usuario específico en la base de datos.
        $sql = "SELECT id, nombre_usuario, password_hash, rol, area_id 
                FROM usuario 
                WHERE nombre_usuario = ? AND estado = 'ACTIVO'";

        // 2. Usamos la conexión que YA EXISTE en $this->pdo, no creamos una nueva.
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$nombre_usuario]);

        // 3. Usamos fetch() porque esperamos UN SOLO resultado, no fetchAll(). Es más eficiente.
        $usuario = $stmt->fetch();

        // 4. Verificamos la contraseña en PHP, solo si se encontró un usuario.
        //    Esta lógica es mucho más directa.
        if ($usuario && password_verify($password, $usuario['password_hash'])) {
            // Si la contraseña coincide, devolvemos los datos del usuario.
            // Es importante quitar el hash del array por seguridad.
            unset($usuario['password_hash']);
            return $usuario;
        }

        // 5. Si el usuario no existe o la contraseña es incorrecta, devolvemos false.
        return false;
    }
}
?>