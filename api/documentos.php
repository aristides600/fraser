<?php
require_once 'db.php';
header('Content-Type: application/json');

// Obtener la solicitud
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

// Operaciones CRUD
switch ($method) {
    case 'GET':
        $sql = "SELECT d.id, d.vehiculo_id, d.tipo_id, d.fecha_vencimiento, d.observacion, 
        v.patente, m.nombre AS marca_nombre, mo.nombre AS modelo_nombre, t.nombre AS tipo_nombre, c.nombre AS color_nombre
        FROM documentos d
        JOIN vehiculos v ON d.vehiculo_id = v.id
        JOIN marcas m ON v.marca_id = m.id
        JOIN modelos mo ON v.modelo_id = mo.id
        JOIN tipos t ON d.tipo_id = t.id
        JOIN colores c ON v.color_id = v.id";
        $result = $conn->query($sql);
        $documentos = [];
        while ($row = $result->fetch_assoc()) {
            $documentos[] = $row;
        }
        echo json_encode($documentos);
        break;
    case 'POST':
        // Obtener los datos del cuerpo de la solicitud
        $data = json_decode(file_get_contents('php://input'), true);

        $vehiculo_id = $data['vehiculo_id'];
        $tipo_id = $data['tipo_id'];
        $fecha_vencimiento = $data['fecha_vencimiento'];
        $observacion = $data['observacion'];

        // Insertar datos en la tabla documentos
        $sql = "INSERT INTO documentos (vehiculo_id, tipo_id, fecha_vencimiento, observacion) VALUES (?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiss", $vehiculo_id, $tipo_id, $fecha_vencimiento, $observacion);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Documento agregado con éxito"]);
        } else {
            echo json_encode(["message" => "Error al agregar el documento"]);
        }

        $stmt->close();
        break;

    case 'PUT':
        // Editar una marca
        $id = $conn->real_escape_string($input['id']);
        $nombre = $conn->real_escape_string(strtoupper($input['nombre']));
        $conn->query("UPDATE documentos SET nombre = '$nombre' WHERE id = '$id'");
        break;
    case 'DELETE':
        // Eliminar una marca
        $id = $conn->real_escape_string($input['id']);
        $conn->query("DELETE FROM documentos WHERE id = '$id'");
        break;
    case 'DELETE':

        $id = $conn->real_escape_string($input['id']);
        $sql = "UPDATE documentos SET estado = false WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(['success' => true, 'message' => 'Marca eliminado con éxito.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el marca.']);
        }
        break;
}

// Cerrar la conexión
$conn->close();
