<?php
require_once __DIR__ . '/Database.php';

class Modelo_Tramite
{

    // --- FUNCIÓN DE LISTADO (La dejamos como estaba) ---
    /**
     * Llama al procedimiento almacenado para obtener la lista de trámites.
     * Mantenemos el nombre Listar_Tramite para coincidir con el video.
     */
    public function Listar_Tramite()
    {
        $pdo = Database::getInstance()->getConnection();
        // Llamamos a nuestro nuevo y mejorado procedimiento
        $sql = "CALL SP_LISTAR_TRAMITE_V2()";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            // Devolvemos todos los resultados
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en SP_LISTAR_TRAMITE_V2: " . $e->getMessage());
            return []; // En caso de error, devolvemos un array vacío
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
     * Lista solo los trámites que pertenecen a un área específica (Bandeja de Entrada).
     */
    public function ListarTramitesPorArea($areaId)
    {
        $pdo = Database::getInstance()->getConnection();

        $sql = "SELECT
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
            LEFT JOIN remitente AS r ON doc.remitente_id = r.id
            WHERE doc.area_actual_id = ?
            ORDER BY doc.id DESC";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$areaId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en ListarTramitesPorArea: " . $e->getMessage());
            return [];
        }
    }
}
?>