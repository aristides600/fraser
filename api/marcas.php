<?php
require_once 'db.php';
header('Content-Type: application/json');

// Obtener la solicitud
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

// Función para verificar si el nombre de la marca ya existe
function marcaExiste($conn, $nombre) {
    $sql = "SELECT id FROM marcas WHERE nombre = :nombre";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':nombre' => $nombre]);
    return $stmt->rowCount() > 0;
}

// Operaciones CRUD
switch ($method) {
    case 'GET':
        // Obtener todas las marcas
        $sql = "SELECT * FROM marcas";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $marcas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($marcas);
        break;

    case 'POST':
        // Agregar una nueva marca
        $nombre = strtoupper($input['nombre']); // Convertir a mayúsculas
        if (marcaExiste($conn, $nombre)) {
            echo json_encode(['success' => false, 'message' => 'La marca ya existe.']);
        } else {
            $sql = "INSERT INTO marcas (nombre) VALUES (:nombre)";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute([':nombre' => $nombre])) {
                echo json_encode(['success' => true, 'message' => 'Marca agregada con éxito.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al agregar la marca.']);
            }
        }
        break;

    case 'PUT':
        // Editar una marca
        $id = $input['id'];
        $nombre = strtoupper($input['nombre']);
        if (marcaExiste($conn, $nombre)) {
            echo json_encode(['success' => false, 'message' => 'La marca ya existe.']);
        } else {
            $sql = "UPDATE marcas SET nombre = :nombre WHERE id = :id";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute([':nombre' => $nombre, ':id' => $id])) {
                echo json_encode(['success' => true, 'message' => 'Marca actualizada con éxito.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar la marca.']);
            }
        }
        break;

    // case 'DELETE':
    //     // Eliminar una marca
    //     $id = $input['id'];
    //     $sql = "UPDATE marcas SET estado = false WHERE id = :id";
    //     $stmt = $conn->prepare($sql);
    //     if ($stmt->execute([':id' => $id])) {
    //         echo json_encode(['success' => true, 'message' => 'Marca eliminada con éxito.']);
    //     } else {
    //         echo json_encode(['success' => false, 'message' => 'Error al eliminar la marca.']);
    //     }
    //     break;
}

// Cerrar la conexión
$conn = null;
?>
