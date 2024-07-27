<?php
require_once 'db.php';
header('Content-Type: application/json');

// Obtener la solicitud
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

// Operaciones CRUD
switch ($method) {
    case 'GET':
        // Obtener todos los modelos
        $result = $conn->query("SELECT m.id, m.nombre, ma.nombre AS marca_nombre FROM modelos m JOIN marcas ma ON m.marca_id = ma.id");
        $modelos = [];
        while ($row = $result->fetch_assoc()) {
            $modelos[] = $row;
        }
        echo json_encode($modelos);
        break;
    case 'POST':
        // Agregar un nuevo modelo
        $nombre = $conn->real_escape_string(strtoupper($input['nombre']));
        $marca_id = $conn->real_escape_string($input['marca_id']);
        $conn->query("INSERT INTO modelos (nombre, marca_id) VALUES ('$nombre', '$marca_id')");
        break;
    case 'PUT':
        // Editar un modelo
        $id = $conn->real_escape_string($input['id']);
        $nombre = $conn->real_escape_string(strtoupper($input['nombre']));
        $marca_id = $conn->real_escape_string($input['marca_id']);
        $conn->query("UPDATE modelos SET nombre = '$nombre', marca_id = '$marca_id' WHERE id = '$id'");
        break;
    case 'DELETE':

        $id = $conn->real_escape_string($input['id']);
        $sql = "UPDATE modelos SET estado = false WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(['success' => true, 'message' => 'Modelo eliminado con éxito.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el modelo.']);
        }
        break;
}

// Cerrar la conexión
$conn->close();
