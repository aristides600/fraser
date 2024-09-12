<?php
require_once 'db.php';
header('Content-Type: application/json');
session_start();

// Obtener la solicitud
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        $sql = "SELECT d.id, d.vehiculo_id, d.tipo_id, d.fecha_vencimiento, d.observacion, 
                v.patente, m.nombre AS marca_nombre, mo.nombre AS modelo_nombre, 
                t.nombre AS tipo_nombre, u.nombre AS usuario_nombre, u.apellido AS usuario_apellido, c.nombre AS color_nombre
                FROM documentos d
                JOIN vehiculos v ON d.vehiculo_id = v.id
                JOIN marcas m ON v.marca_id = m.id
                JOIN modelos mo ON v.modelo_id = mo.id
                JOIN tipos t ON d.tipo_id = t.id
                JOIN usuarios u ON d.usuario_id = u.id
                JOIN colores c ON v.color_id = c.id 
                WHERE d.estado = 1
                ORDER BY d.fecha_vencimiento ASC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $documentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($documentos);
        break;

    case 'POST':
        date_default_timezone_set('America/Argentina/Buenos_Aires');

        $vehiculo_id = $input['vehiculo_id'];
        $tipo_id = $input['tipo_id'];
        $fecha_vencimiento = $input['fecha_vencimiento'];
        $observacion = $input['observacion'];
        $usuario_id = $_SESSION['user_id'];
        $estado = 1;

        // Verificar si ya existe un documento para este vehículo y tipo
        $checkQuery = "SELECT id FROM documentos WHERE vehiculo_id = ? AND tipo_id = ? AND estado != 0";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->execute([$vehiculo_id, $tipo_id]);
        
        if ($checkStmt->rowCount() > 0) {
            // Si existe, devolver un error 409 (conflicto)
            http_response_code(409);
            echo json_encode(['message' => 'Ya existe un documento de este tipo para el vehículo seleccionado']);
        } else {
            $sql = "INSERT INTO documentos (vehiculo_id, tipo_id, fecha_alta, fecha_vencimiento, observacion, usuario_id, estado)
                    VALUES (?, ?, NOW(), ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$vehiculo_id, $tipo_id, $fecha_vencimiento, $observacion, $usuario_id, $estado]);

            if ($stmt->rowCount() > 0) {
                echo json_encode(["message" => "Documento agregado con éxito"]);
            } else {
                echo json_encode(["message" => "Error al agregar el documento"]);
            }
        }
        break;

    case 'PUT':
        $id = $input['id'];
        $vehiculo_id = $input['vehiculo_id'];
        $tipo_id = $input['tipo_id'];
        $fecha_vencimiento = $input['fecha_vencimiento'];
        $observacion = $input['observacion'];

        // Verificar si ya existe un documento para este vehículo y tipo
        $checkQuery = "SELECT id FROM documentos WHERE vehiculo_id = ? AND tipo_id = ? AND id != ? AND estado != 0";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->execute([$vehiculo_id, $tipo_id, $id]);

        if ($checkStmt->rowCount() > 0) {
            http_response_code(409);
            echo json_encode(['message' => 'Ya existe un documento de este tipo para el vehículo seleccionado']);
        } else {
            $sql = "UPDATE documentos SET tipo_id = ?, fecha_vencimiento = ?, observacion = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$tipo_id, $fecha_vencimiento, $observacion, $id]);

            if ($stmt->rowCount() > 0) {
                echo json_encode(['message' => 'Documento editado correctamente']);
            } else {
                http_response_code(500);
                echo json_encode(['message' => 'Error al editar el documento']);
            }
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['message' => 'Método no permitido']);
        break;
}

// Cerrar la conexión
$conn = null;
?>
