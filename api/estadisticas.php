<?php
require_once 'db.php'; // Incluye el archivo de conexión

header('Content-Type: application/json');

// Verificar si la conexión ya fue creada
if ($conn->connect_error) {
    die("Error al conectar con la base de datos: " . $conn->connect_error);
}

// Consulta SQL
$sql = "SELECT 
           SUM(CASE WHEN estado = 1 THEN 1 ELSE 0 END) AS vigentes,
           SUM(CASE WHEN estado = 0 THEN 1 ELSE 0 END) AS vencidos
        FROM documentos";
        
$result = $conn->query($sql);

// Verifica si hay resultados
if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    echo json_encode($data);
} else {
    echo json_encode(['vigentes' => 0, 'vencidos' => 0]);
}

$conn->close();
?>
