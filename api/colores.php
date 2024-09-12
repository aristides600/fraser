<?php
require_once 'db.php';
header('Content-Type: application/json');

// Obtener la solicitud
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

// Función para verificar si el nombre del color ya existe
function colorExiste($conn, $nombre) {
    $sql = "SELECT id FROM colores WHERE nombre = :nombre";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':nombre' => $nombre]);
    return $stmt->rowCount() > 0;
}

// Operaciones CRUD
switch ($method) {
    case 'GET':
        // Obtener todos los colores
        $sql = "SELECT * FROM colores";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $colores = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($colores);
        break;

    case 'POST':
        // Agregar un nuevo color
        $nombre = strtoupper($input['nombre']); // Convertir a mayúsculas
        if (colorExiste($conn, $nombre)) {
            echo json_encode(['success' => false, 'message' => 'El color ya existe.']);
        } else {
            $sql = "INSERT INTO colores (nombre) VALUES (:nombre)";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute([':nombre' => $nombre])) {
                echo json_encode(['success' => true, 'message' => 'Color agregado con éxito.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al agregar el color.']);
            }
        }
        break;

    case 'PUT':
        // Editar un color
        $id = $input['id'];
        $nombre = strtoupper($input['nombre']);
        if (colorExiste($conn, $nombre)) {
            echo json_encode(['success' => false, 'message' => 'El color ya existe.']);
        } else {
            $sql = "UPDATE colores SET nombre = :nombre WHERE id = :id";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute([':nombre' => $nombre, ':id' => $id])) {
                echo json_encode(['success' => true, 'message' => 'Color actualizado con éxito.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar el color.']);
            }
        }
        break;

    // case 'DELETE':
    //     // Eliminar un color (lógico)
    //     $id = $input['id'];
    //     $sql = "UPDATE colores SET estado = false WHERE id = :id";
    //     $stmt = $conn->prepare($sql);
    //     if ($stmt->execute([':id' => $id])) {
    //         echo json_encode(['success' => true, 'message' => 'Color eliminado con éxito.']);
    //     } else {
    //         echo json_encode(['success' => false, 'message' => 'Error al eliminar el color.']);
    //     }
    //     break;
}

// Cerrar la conexión
$conn = null;
?>
