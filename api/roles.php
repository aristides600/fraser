<?php
require_once 'db.php';
header('Content-Type: application/json');

// Obtener la solicitud
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);


switch ($method) {
    case 'GET':
        // Obtener todos los roles
        $result = $conn->query("SELECT * FROM roles");
        $roles = [];
        while ($row = $result->fetch_assoc()) {
            $roles[] = $row;
        }
        echo json_encode($roles);
        break;
    
}

// Cerrar la conexión
$conn->close();
?>
