<?php
// Conexión a la base de datos
require_once 'db.php'; // Asegúrate de que este archivo contenga la conexión PDO

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'data') {
        $patente = isset($_GET['patente']) ? $_GET['patente'] : '';

        // Consulta para obtener documentos y vehículos
        $query = "
            SELECT d.id, v.patente, m.nombre AS marca, mo.nombre AS modelo, v.anio, c.nombre AS color, d.fecha_alta, d.fecha_vencimiento, d.observacion, td.fecha_tramite, u.nombre, u.apellido, t.nombre AS tipo_documento
            FROM documentos d
            JOIN vehiculos v ON d.vehiculo_id = v.id
            JOIN marcas m ON v.marca_id = m.id
            JOIN modelos mo ON v.modelo_id = mo.id
            JOIN colores c ON v.color_id = c.id
            JOIN tramites_documentos td ON d.id = td.documento_id
            JOIN usuarios u ON td.usuario_id = u.id
            JOIN tipos t ON d.tipo_id = t.id
        ";

        if ($patente !== '') {
            $query .= " WHERE v.patente LIKE :patente";
        }

        try {
            // Preparar la declaración
            $stmt = $conn->prepare($query);

            if ($patente !== '') {
                // Bind del parámetro
                $patenteLike = "%$patente%";
                $stmt->bindParam(':patente', $patenteLike, PDO::PARAM_STR);
            }

            // Ejecutar la consulta
            $stmt->execute();

            // Obtener los resultados
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Devolver los resultados como JSON
            echo json_encode($data);
        } catch (PDOException $e) {
            // Manejo de errores
            echo json_encode(['success' => false, 'message' => 'Error en la consulta: ' . $e->getMessage()]);
        }
    }

    // Cerrar la conexión a la base de datos
    $conn = null;
    exit;
}
?>
