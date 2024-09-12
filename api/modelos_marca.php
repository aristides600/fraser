<?php
require_once 'db.php'; // Incluye tu archivo de conexión PDO

// Obtener la marca_id de la URL
$marca_id = $_GET['marca_id'];

try {
    // Consulta SQL para obtener los modelos de una marca
    $sql = "SELECT * FROM modelos WHERE marca_id = :marca_id";
    
    // Preparar la declaración
    $stmt = $conn->prepare($sql);
    
    // Bind del parámetro
    $stmt->bindParam(':marca_id', $marca_id, PDO::PARAM_INT);
    
    // Ejecutar la consulta
    $stmt->execute();
    
    // Obtener los resultados
    $modelos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Devolver los resultados como JSON
    echo json_encode($modelos);
    
} catch (PDOException $e) {
    // Manejo de errores
    echo json_encode(['success' => false, 'message' => 'Error en la consulta: ' . $e->getMessage()]);
}

// Cerrar la conexión a la base de datos
$conn = null;
?>
