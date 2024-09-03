<?php
require_once 'db.php';

$input = json_decode(file_get_contents('php://input'), true);
$dni = $conn->real_escape_string($input['dni']); 

$result = $conn->query("SELECT COUNT(*) AS count FROM usuarios WHERE dni = '$dni'");
$count = $result->fetch_assoc()['count'];

echo json_encode(['existe' => $count > 0]);

$conn->close();
?>
