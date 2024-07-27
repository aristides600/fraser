<?php
require_once 'db.php';
header('Content-Type: application/json');

// Obtener la solicitud
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

// Función para verificar si el nombre de la marca ya existe
function marcaExiste($conn, $nombre) {
    $nombre = $conn->real_escape_string($nombre);
    $result = $conn->query("SELECT id FROM marcas WHERE nombre = '$nombre'");
    return $result->num_rows > 0;
}

// Operaciones CRUD
switch ($method) {
    case 'GET':
        // Obtener todos los marcas
        $result = $conn->query("SELECT * FROM marcas");
        $marcas = [];
        while ($row = $result->fetch_assoc()) {
            $marcas[] = $row;
        }
        echo json_encode($marcas);
        break;
    case 'POST':
        // Agregar una nueva marca
        $nombre = strtoupper($conn->real_escape_string($input['nombre'])); // Convertir a mayúsculas
        if (marcaExiste($conn, $nombre)) {
            echo json_encode(['success' => false, 'message' => 'La marca ya existe.']);
        } else {
            $conn->query("INSERT INTO marcas (nombre) VALUES ('$nombre')");
            echo json_encode(['success' => true, 'message' => 'Marca agregada con éxito.']);
        }
        break;
    case 'PUT':
        // Editar una marca
        $id = $conn->real_escape_string($input['id']);
        $nombre = strtoupper($conn->real_escape_string($input['nombre']));
        if (marcaExiste($conn, $nombre)) {
            echo json_encode(['success' => false, 'message' => 'La marca ya existe.']);
        } else {
            $conn->query("UPDATE marcas SET nombre = '$nombre' WHERE id = '$id'");
            echo json_encode(['success' => true, 'message' => 'Marca actualizada con éxito.']);
        }
        break;
    case 'DELETE':
        // Eliminar una marca
        $id = $conn->real_escape_string($input['id']);
        $conn->query("UPDATE marcas SET estado = false WHERE id = '$id'");
        echo json_encode(['success' => true, 'message' => 'Marca eliminada con éxito.']);
        break;
}

// Cerrar la conexión
$conn->close();
?>
