<?php
require_once __DIR__ . '/Database.php';

class Modelo_Tramite
{

    /**
     * Llama al procedimiento almacenado para listar trámites, con filtro opcional por área.
     * Si $areaId es null, trae todos los trámites (para el Admin).
     * Si $areaId tiene un valor, trae solo los trámites relevantes para esa área.
     */
    public function ListarTramites($areaId = null)
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = "CALL SP_LISTAR_TRAMITES_FILTRADO(?)";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$areaId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en SP_LISTAR_TRAMITES_FILTRADO: " . $e->getMessage());
            return [];
        }
    }

    // --- FUNCIÓN DE REGISTRO (LA MÁS IMPORTANTE) ---
    public function RegistrarTramite($tipoDocId, $nroDoc, $asunto, $nroFolios, $usuarioId, $areaOrigenId, $areaDestinoId, $remitenteId = null, $nombreArchivo = null)
    {
        $pdo = Database::getInstance()->getConnection();

        try {
            $pdo->beginTransaction();

            // 1. Insertamos el documento. Su 'area_actual_id' es la del creador.
            $sql_doc = "INSERT INTO documento (tipo_documento_id, nro_documento, asunto, nro_folios, 
                                             usuario_creador_id, area_origen_id, area_actual_id, 
                                             remitente_id, archivo_pdf, estado_general) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'EN TRAMITE')";

            $stmt_doc = $pdo->prepare($sql_doc);
            $stmt_doc->execute([$tipoDocId, $nroDoc, $asunto, $nroFolios, $usuarioId, $areaOrigenId, $areaOrigenId, $remitenteId, $nombreArchivo]);

            $docId = $pdo->lastInsertId(); // Obtenemos el ID del documento recién creado.

            // 2. Construimos el código único.
            $codigoUnico = 'CU' . str_pad($docId, 6, "0", STR_PAD_LEFT);

            // 3. Actualizamos el registro con el código único.
            $sql_update = "UPDATE documento SET codigo_unico = ? WHERE id = ?";
            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->execute([$codigoUnico, $docId]);

            // 4. REGISTRAMOS EL PRIMER MOVIMIENTO (EL "PASE" INICIAL)
            $sql_mov = "INSERT INTO movimiento (documento_id, area_origen_id, area_destino_id, usuario_id, estado_movimiento, proveido) 
                        VALUES (?, ?, ?, ?, 'ENVIADO', 'Trámite Registrado')";

            $stmt_mov = $pdo->prepare($sql_mov);
            $stmt_mov->execute([$docId, $areaOrigenId, $areaDestinoId, $usuarioId]);

            // 5. ¡ÉXITO! Confirmamos todos los cambios.
            $pdo->commit();

            return "OK";

        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log("Error en RegistrarTramite: " . $e->getMessage());
            return "ERROR_EXCEPTION";
        }
    }

    // --- FUNCIONES AYUDANTES PARA LOS COMBOS (NUEVAS) ---
    public function ListarTiposDocumentoActivos()
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = "SELECT id, nombre FROM tipo_documento WHERE estado = 'ACTIVO'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    // Añadimos esta función para listar las áreas en el combo de destino
    public function ListarAreasActivas()
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = "SELECT id, nombre FROM area WHERE estado = 'ACTIVO'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    // Aquí puedes añadir una función para listar remitentes si tienes una tabla para ellos

    /**
     * Esta es la consulta base que usaremos en todas las funciones de listado.
     * La definimos una sola vez para evitar errores y duplicación de código.
     */
    private $selectBase = "SELECT
                                doc.id,
                                doc.codigo_unico,
                                doc.nro_documento,
                                doc.fecha_registro,
                                td.nombre AS tipo_documento_nombre,
                                doc.asunto,
                                doc.nro_folios,
                                aa.nombre AS area_actual,
                                doc.estado_general AS estado_destino,
                                doc.archivo_pdf,
                                COALESCE(r.nombres_razon_social, CONCAT_WS(' ', emp.nombres, emp.apellido_paterno)) AS remitente_principal,
                                (
                                    SELECT mov.proveido 
                                    FROM movimiento AS mov 
                                    WHERE mov.documento_id = doc.id 
                                    ORDER BY mov.id DESC 
                                    LIMIT 1
                                ) AS proveido
                            FROM
                                documento AS doc
                            INNER JOIN tipo_documento AS td ON doc.tipo_documento_id = td.id
                            INNER JOIN area AS aa ON doc.area_actual_id = aa.id
                            INNER JOIN usuario AS u ON doc.usuario_creador_id = u.id
                            INNER JOIN empleado AS emp ON u.empleado_id = emp.id
                            LEFT JOIN remitente AS r ON doc.remitente_id = r.id";



    /**
     * Lista los trámites en los que el área del usuario ha participado.
     * ESTA ES LA FUNCIÓN QUE HEMOS MEJORADO.
     */
    public function ListarTramitesPorArea($areaId)
    {
        $pdo = Database::getInstance()->getConnection();

        // Ahora, el WHERE es más inteligente. Busca si el areaId del usuario
        // existe en CUALQUIER movimiento (origen o destino) de un documento.
        $sql = $this->selectBase . " WHERE 
                                        EXISTS (
                                            SELECT 1 
                                            FROM movimiento m 
                                            WHERE m.documento_id = doc.id 
                                            AND (m.area_origen_id = ? OR m.area_destino_id = ?)
                                        )
                                    ORDER BY doc.id DESC";
        try {
            $stmt = $pdo->prepare($sql);
            // Pasamos el ID del área dos veces, una para cada condición del OR.
            $stmt->execute([$areaId, $areaId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en ListarTramitesPorArea: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene el historial completo de movimientos (trazabilidad) para un documento específico.
     */
    public function VerSeguimiento($documentoId)
    {
        $pdo = Database::getInstance()->getConnection();

        $sql = "SELECT
                mov.fecha_movimiento,
                orig.nombre AS area_origen,
                dest.nombre AS area_destino,
                CONCAT_WS(' ', emp.nombres, emp.apellido_paterno) AS usuario_nombre,
                mov.estado_movimiento,
                mov.proveido
            FROM
                movimiento AS mov
            INNER JOIN area AS orig ON mov.area_origen_id = orig.id
            INNER JOIN area AS dest ON mov.area_destino_id = dest.id
            INNER JOIN usuario AS u ON mov.usuario_id = u.id
            INNER JOIN empleado AS emp ON u.empleado_id = emp.id
            WHERE mov.documento_id = ?
            ORDER BY mov.id ASC"; // Ordenamos del más antiguo al más reciente

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$documentoId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en VerSeguimiento: " . $e->getMessage());
            return [];
        }
    }

    public function DerivarTramite($documentoId, $areaOrigenId, $areaDestinoId, $proveido, $usuarioId)
    {
        $pdo = Database::getInstance()->getConnection();

        try {
            $pdo->beginTransaction();

            // 1. Actualizamos el documento, cambiando su 'area_actual_id' al nuevo destino.
            $sql_update_doc = "UPDATE documento SET area_actual_id = ? WHERE id = ?";
            $stmt_update_doc = $pdo->prepare($sql_update_doc);
            $stmt_update_doc->execute([$areaDestinoId, $documentoId]);

            // 2. Insertamos el nuevo movimiento en la tabla de trazabilidad.
            $sql_insert_mov = "INSERT INTO movimiento (documento_id, area_origen_id, area_destino_id, usuario_id, estado_movimiento, proveido)
                               VALUES (?, ?, ?, ?, 'DERIVADO', ?)";
            $stmt_insert_mov = $pdo->prepare($sql_insert_mov);
            $stmt_insert_mov->execute([$documentoId, $areaOrigenId, $areaDestinoId, $usuarioId, $proveido]);

            // 3. Si todo fue exitoso, confirmamos los cambios.
            $pdo->commit();
            return "OK";

        } catch (PDOException $e) {
            // 4. Si algo falló, revertimos todo.
            $pdo->rollBack();
            error_log("Error en DerivarTramite: " . $e->getMessage());
            return "ERROR_EXCEPTION";
        }
    }
}
?>