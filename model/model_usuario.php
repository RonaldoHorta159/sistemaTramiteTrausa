<?php
require_once __DIR__ . '/Database.php';

class Modelo_Usuario
{

    // --- FUNCIÓN DE LISTADO (YA LA TENÍAMOS, PERO LA MEJORAMOS) ---
    public function ListarUsuarios()
    {
        $pdo = Database::getInstance()->getConnection();
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
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en ListarUsuarios: " . $e->getMessage());
            return [];
        }
    }

    // --- FUNCIÓN DE REGISTRO (NUEVA) ---
    public function RegistrarUsuario($empleadoId, $areaId, $nombreUsuario, $password, $rol)
    {
        $pdo = Database::getInstance()->getConnection();

        // Verificamos que el nombre de usuario no exista
        $sql_verificar = "SELECT COUNT(*) FROM usuario WHERE nombre_usuario = ?";
        $stmt_verificar = $pdo->prepare($sql_verificar);
        $stmt_verificar->execute([$nombreUsuario]);
        if ($stmt_verificar->fetchColumn() > 0) {
            return "EXISTE_USUARIO";
        }

        // Hasheamos la contraseña para guardarla de forma segura
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $sql_insertar = "INSERT INTO usuario (empleado_id, area_id, nombre_usuario, password_hash, rol) 
                         VALUES (?, ?, ?, ?, ?)";
        try {
            $stmt_insertar = $pdo->prepare($sql_insertar);
            if ($stmt_insertar->execute([$empleadoId, $areaId, $nombreUsuario, $passwordHash, $rol])) {
                return "OK";
            }
            return "ERROR";
        } catch (PDOException $e) {
            error_log("Error en RegistrarUsuario: " . $e->getMessage());
            return "ERROR_EXCEPTION";
        }
    }

    // --- FUNCIÓN DE EDICIÓN (NUEVA) ---
    public function EditarUsuario($id, $areaId, $rol, $estado)
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = "UPDATE usuario SET area_id = ?, rol = ?, estado = ? WHERE id = ?";
        try {
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$areaId, $rol, $estado, $id])) {
                return "OK";
            }
            return "ERROR";
        } catch (PDOException $e) {
            error_log("Error en EditarUsuario: " . $e->getMessage());
            return "ERROR_EXCEPTION";
        }
    }

    // --- FUNCIÓN PARA CAMBIAR CONTRASEÑA (NUEVA Y SEGURA) ---
    public function CambiarPassword($id, $newPassword)
    {
        $pdo = Database::getInstance()->getConnection();
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE usuario SET password_hash = ? WHERE id = ?";
        try {
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$passwordHash, $id])) {
                return "OK";
            }
            return "ERROR";
        } catch (PDOException $e) {
            error_log("Error en CambiarPassword: " . $e->getMessage());
            return "ERROR_EXCEPTION";
        }
    }

    // --- FUNCIÓN DE ELIMINACIÓN (NUEVA) ---
    public function EliminarUsuario($id)
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = "DELETE FROM usuario WHERE id = ?";
        try {
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$id])) {
                return "OK";
            }
            return "ERROR";
        } catch (PDOException $e) {
            error_log("Error en EliminarUsuario: " . $e->getMessage());
            return "ERROR_FK";
        }
    }

    // --- FUNCIONES AYUDANTES PARA LLENAR LOS COMBOS (NUEVAS) ---

    // Lista solo los empleados que AÚN NO tienen una cuenta de usuario
    public function ListarEmpleadosSinUsuario()
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = "SELECT id, CONCAT_WS(' ', nombres, apellido_paterno, apellido_materno) AS nombre_completo 
                FROM empleado 
                WHERE id NOT IN (SELECT empleado_id FROM usuario)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lista todas las áreas activas
    public function ListarAreasActivas()
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = "SELECT id, nombre FROM area WHERE estado = 'ACTIVO'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // --- FUNCIÓN PARA EL LOGIN (YA LA TENÍAMOS, LA MANTENEMOS) ---
    public function VerificarUsuario($usuario)
    {
        $pdo = Database::getInstance()->getConnection();

        // --- CONSULTA MEJORADA: Ahora también trae area_id y rol ---
        $sql = "SELECT 
                id AS usu_id, 
                nombre_usuario AS usu_nombre, 
                password_hash AS usu_contra,
                area_id,
                rol
            FROM usuario WHERE nombre_usuario = :usuario";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':usuario', $usuario);
            $stmt->execute();
            // Usamos FETCH_OBJ para que sea más fácil acceder a las propiedades
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log("Error en VerificarUsuario: " . $e->getMessage());
            return [];
        }
    }
}
?>