<?php
include 'db.php'; // Conexión a la base de datos
header('Content-Type: application/json');

session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit;
}

// Verificar el método de la solicitud
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Establecer la zona horaria
date_default_timezone_set('America/Argentina/Buenos_Aires');
$fecha_tramite = date('Y-m-d H:i:s'); // Fecha y hora actual

// Obtener los datos de la solicitud
$documento_id = $_POST['documento_id'] ?? null;
$usuario_id = $_SESSION['user_id'];
$observacion = $_POST['observacion'] ?? null;

// Verificar si los datos necesarios están presentes
if (!empty($documento_id) && !empty($usuario_id) && !empty($observacion)) {
    // Iniciar una transacción para asegurar la atomicidad de las operaciones
    $conn->beginTransaction();

    try {
        // Insertar en la tabla tramites_documentos
        $query = "INSERT INTO tramites_documentos (documento_id, usuario_id, fecha_tramite, observacion) 
                  VALUES (:documento_id, :usuario_id, :fecha_tramite, :observacion)";
        $stmt = $conn->prepare($query);

        if (!$stmt->execute([
            ':documento_id' => $documento_id,
            ':usuario_id' => $usuario_id,
            ':fecha_tramite' => $fecha_tramite,
            ':observacion' => $observacion
        ])) {
            throw new Exception('Error en la ejecución de la consulta: ' . implode(' ', $stmt->errorInfo()));
        }

        // Actualizar la tabla documentos
        $updateQuery = "UPDATE documentos SET estado = false WHERE id = :documento_id";
        $updateStmt = $conn->prepare($updateQuery);

        if (!$updateStmt->execute([':documento_id' => $documento_id])) {
            throw new Exception('Error en la ejecución de la consulta de actualización: ' . implode(' ', $updateStmt->errorInfo()));
        }

        // Confirmar la transacción
        $conn->commit();
        echo json_encode(['success' => true]);

    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollBack();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
}

// Cerrar la conexión a la base de datos
$conn = null;
?>
