<?php
session_start();
require_once 'db.php';

// Verificar si el usuario está logueado
if (isset($_SESSION['user_id'])) {
    // Obtener rol del usuario
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT rol_id FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $rol_id = 0;

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $rol_id = $row['rol_id'];
    } else {
        // Si el usuario no existe, cerrar sesión y redirigir a login
        session_destroy();
        header('Location: login.php');
        exit();
    }

    // Redirigir al index si el usuario está logueado
    header('Location: index.php');
    exit();
}
