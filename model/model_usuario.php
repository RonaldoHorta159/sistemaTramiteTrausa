<?php
// 1. Incluimos tu clase de conexión a la base de datos.
// La ruta es '../model/Database.php' si el modelo de usuario está en la misma carpeta,
// o una ruta diferente si está en otro lugar. Asumiendo que está en la misma carpeta 'model'.
require_once __DIR__ . '/Database.php';

// 2. Definimos la clase con el nombre exacto que el controlador espera.
class Modelo_Usuario
{

    // (Aquí puedes agregar otras funciones del usuario en el futuro)

    /**
     * Función para obtener todos los usuarios y sus datos relacionados.
     */
    public function ListarUsuarios()
    {
        // Obtenemos la conexión PDO usando tu clase Database (patrón Singleton).
        $pdo = Database::getInstance()->getConnection();

        // La consulta SQL que une las tres tablas para obtener la información necesaria.
        $sql = "SELECT
                    u.id,
                    u.nombre_usuario,
                    CONCAT_WS(' ', e.nombres, e.apellido_paterno, e.apellido_materno) AS empleado_nombre,
                    a.nombre AS area_nombre,
                    u.rol,
                    u.estado
                FROM
                    usuario AS u
                INNER JOIN empleado AS e ON u.empleado_id = e.id
                INNER JOIN area AS a ON u.area_id = a.id";

        try {
            // Preparamos y ejecutamos la consulta.
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            // Devolvemos todos los resultados.
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            // En caso de un error en la consulta, devolvemos un array vacío.
            // También es buena idea registrar el error.
            error_log("Error en ListarUsuarios: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Función para verificar las credenciales de un usuario al iniciar sesión.
     * La necesitas para que tu login siga funcionando.
     */
    public function VerificarUsuario($usuario)
    {
        $pdo = Database::getInstance()->getConnection();

        $sql = "SELECT id as usu_id, nombre_usuario as usu_nombre, password_hash as usu_contra FROM usuario WHERE nombre_usuario = :usuario";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':usuario', $usuario);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log("Error en VerificarUsuario: " . $e->getMessage());
            return [];
        }
    }
}
?>