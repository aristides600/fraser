<?php
include 'db.php'; // Incluye tu archivo de conexión PDO

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Preparar y ejecutar la consulta
        $query = $conn->prepare("SELECT * FROM documentos WHERE id = :id");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $documento = $query->fetch(PDO::FETCH_ASSOC);

        if ($documento) {
            echo json_encode($documento);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Documento no encontrado']);
        }
    } catch (PDOException $e) {
        // Manejo de errores de la base de datos
        http_response_code(500);
        echo json_encode(['message' => 'Error en la consulta', 'error' => $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['message' => 'ID de documento no proporcionado']);
}

// Cerrar la conexión a la base de datos
$conn = null;
?>
