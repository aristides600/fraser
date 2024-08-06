<?php
require_once 'db.php';
header('Content-Type: application/json');

// Obtener la solicitud
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

// Función para verificar si el nombre del color ya existe
function colorExiste($conn, $nombre) {
    $nombre = $conn->real_escape_string($nombre);
    $result = $conn->query("SELECT id FROM colores WHERE nombre = '$nombre'");
    return $result->num_rows > 0;
}

// Operaciones CRUD
switch ($method) {
    case 'GET':
        // Obtener todos los colores
        $result = $conn->query("SELECT * FROM colores");
        $colores = [];
        while ($row = $result->fetch_assoc()) {
            $colores[] = $row;
        }
        echo json_encode($colores);
        break;
    case 'POST':
        // Agregar un nuevo color
        $nombre = strtoupper($conn->real_escape_string($input['nombre'])); // Convertir a mayúsculas
        if (colorExiste($conn, $nombre)) {
            echo json_encode(['success' => false, 'message' => 'El color ya existe.']);
        } else {
            $conn->query("INSERT INTO colores (nombre) VALUES ('$nombre')");
            echo json_encode(['success' => true, 'message' => 'Color agregado con éxito.']);
        }
        break;
    case 'PUT':
        // Editar un color
        $id = $conn->real_escape_string($input['id']);
        $nombre = strtoupper($conn->real_escape_string($input['nombre']));
        if (colorExiste($conn, $nombre)) {
            echo json_encode(['success' => false, 'message' => 'El color ya existe.']);
        } else {
            $conn->query("UPDATE colores SET nombre = '$nombre' WHERE id = '$id'");
            echo json_encode(['success' => true, 'message' => 'Color actualizado con éxito.']);
        }
        break;
    // case 'DELETE':
    //     // Eliminar un color (lógico)
    //     $id = $conn->real_escape_string($input['id']);
    //     $conn->query("UPDATE colores SET estado = false WHERE id = '$id'");
    //     echo json_encode(['success' => true, 'message' => 'Color eliminado con éxito.']);
    //     break;
}

// Cerrar la conexión
$conn->close();
?>
