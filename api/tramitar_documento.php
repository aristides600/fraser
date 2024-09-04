<?php
include 'db.php'; // Conexión a la base de datos
header('Content-Type: application/json');

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$documento_id = $_POST['documento_id'] ?? null;
$usuario_id = $_SESSION['user_id'];
$fecha_tramite = date('Y-m-d H:i:s');
$observacion = $_POST['observacion'] ?? null;

if (!empty($documento_id) && !empty($usuario_id) && !empty($observacion)) {
    $query = "INSERT INTO tramites_documentos (documento_id, usuario_id, fecha_tramite, observacion) 
              VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        echo json_encode(['success' => false, 'error' => 'Error en la preparación de la consulta: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param("iiss", $documento_id, $usuario_id, $fecha_tramite, $observacion);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error en la ejecución de la consulta: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
}

$conn->close();
