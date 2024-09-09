<?php
// Conexión a la base de datos
include 'db.php';

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
            $query .= " WHERE v.patente LIKE ?";
        }

        $stmt = $conn->prepare($query);

        if ($patente !== '') {
            $stmt->bind_param('s', $patenteLike);
            $patenteLike = "%$patente%";
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $data = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        echo json_encode($data);
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
    exit;
}
?>
