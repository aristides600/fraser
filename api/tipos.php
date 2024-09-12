<?php
require_once 'db.php';
header('Content-Type: application/json');

// Obtener todos los tipos
$sql = "SELECT id, nombre FROM tipos";
$stmt = $conn->prepare($sql);
$stmt->execute();

$tipos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($tipos);

$conn = null; // Cierra la conexión
?>
