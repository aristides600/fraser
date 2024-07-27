<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = $conn->prepare("SELECT * FROM documentos WHERE id = ?");
    $query->bind_param('i', $id);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows > 0) {
        $documento = $result->fetch_assoc();
        echo json_encode($documento);
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'Documento no encontrado']);
    }
} else {
    http_response_code(400);
    echo json_encode(['message' => 'ID de documento no proporcionado']);
}
?>
