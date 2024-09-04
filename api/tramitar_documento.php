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
    // Iniciar una transacción para asegurar la atomicidad de las operaciones
    $conn->begin_transaction();

    try {
        // Insertar en la tabla tramites_documentos
        $query = "INSERT INTO tramites_documentos (documento_id, usuario_id, fecha_tramite, observacion) 
                  VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);

        if ($stmt === false) {
            throw new Exception('Error en la preparación de la consulta: ' . $conn->error);
        }

        $stmt->bind_param("iiss", $documento_id, $usuario_id, $fecha_tramite, $observacion);

        if (!$stmt->execute()) {
            throw new Exception('Error en la ejecución de la consulta: ' . $stmt->error);
        }

        // Actualizar la tabla documentos
        $updateQuery = "UPDATE documentos SET estado = false WHERE id = ?";
        $updateStmt = $conn->prepare($updateQuery);

        if ($updateStmt === false) {
            throw new Exception('Error en la preparación de la consulta de actualización: ' . $conn->error);
        }

        $updateStmt->bind_param("i", $documento_id);

        if (!$updateStmt->execute()) {
            throw new Exception('Error en la ejecución de la consulta de actualización: ' . $updateStmt->error);
        }

        // Confirmar la transacción
        $conn->commit();
        echo json_encode(['success' => true]);

        // Cerrar las declaraciones preparadas
        $stmt->close();
        $updateStmt->close();
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollback();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
}

$conn->close();
