<?php
// cambiar_contrasena.php
session_start();
require 'db.php'; // Incluye tu conexión a la base de datos

// Obtener datos enviados desde el frontend
$data = json_decode(file_get_contents("php://input"), true);
$currentPassword = $data['currentPassword'];
$newPassword = $data['newPassword'];

// Obtener el ID del usuario logeado
$userId = $_SESSION['user_id'];

// Verificar la contraseña actual
$query = $conn->prepare("SELECT clave FROM usuarios WHERE id = ?");
$query->bind_param("i", $userId);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

if (password_verify($currentPassword, $user['clave'])) {
    // Encriptar la nueva contraseña
    $newPasswordHashed = password_hash($newPassword, PASSWORD_BCRYPT);

    // Actualizar la contraseña en la base de datos
    $updateQuery = $conn->prepare("UPDATE usuarios SET clave = ? WHERE id = ?");
    $updateQuery->bind_param("si", $newPasswordHashed, $userId);

    if ($updateQuery->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar la contraseña.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Contraseña actual incorrecta.']);
}

$conn->close();
?>
