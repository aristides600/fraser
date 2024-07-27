<?php
include 'db.php';

// Obtener todos los modelos
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT m.id, m.nombre AS nombre, ma.nombre AS marca_nombre, m.marca_id FROM modelos m JOIN marcas ma ON m.marca_id = ma.id";
    $result = $conn->query($sql);

    $modelos = [];
    while ($row = $result->fetch_assoc()) {
        $modelos[] = $row;
    }

    echo json_encode($modelos);
    exit;
}

// Crear un nuevo modelo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $nombre = $data['nombre'];
    $marca_id = $data['marca_id'];

    // Verificar si el modelo ya existe
    $sql = "SELECT * FROM modelos WHERE nombre = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nombre);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        http_response_code(400);
        echo json_encode(['error' => 'El nombre del modelo ya existe']);
        exit;
    }

    // Insertar el nuevo modelo
    $sql = "INSERT INTO modelos (nombre, marca_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $nombre, $marca_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => 'Modelo creado exitosamente']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Error al crear el modelo']);
    }
    exit;
}

// Actualizar un modelo
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];
    $nombre = $data['nombre'];
    $marca_id = $data['marca_id'];

    // Verificar si el modelo ya existe
    $sql = "SELECT * FROM modelos WHERE nombre = ? AND id != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $nombre, $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        http_response_code(400);
        echo json_encode(['error' => 'El nombre del modelo ya existe']);
        exit;
    }

    // Actualizar el modelo
    $sql = "UPDATE modelos SET nombre = ?, marca_id = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $nombre, $marca_id, $id);
    if ($stmt->execute()) {
        echo json_encode(['success' => 'Modelo actualizado exitosamente']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Error al actualizar el modelo']);
    }
    exit;
}

// Eliminar un modelo
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];

    $sql = "DELETE FROM modelos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo json_encode(['success' => 'Modelo eliminado exitosamente']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Error al eliminar el modelo']);
    }
    exit;
}
?>