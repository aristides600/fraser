<?php
require_once 'db.php'; // Incluye tu archivo de conexión PDO
header('Content-Type: application/json');

// Obtener el parámetro patente
$patente = $_GET['patente'];

try {
    // Preparar la consulta SQL
    $sql = "SELECT v.id, v.patente, c.nombre AS color, m.nombre AS marca, mo.nombre AS modelo, v.anio 
            FROM vehiculos v
            JOIN marcas m ON v.marca_id = m.id
            JOIN modelos mo ON v.modelo_id = mo.id
            JOIN colores c ON v.color_id = c.id
            WHERE v.patente LIKE :patente";
    
    // Preparar la declaración
    $stmt = $conn->prepare($sql);
    
    // Bind del parámetro
    $patente = "%$patente%";
    $stmt->bindParam(':patente', $patente, PDO::PARAM_STR);
    
    // Ejecutar la consulta
    $stmt->execute();
    
    // Obtener los resultados
    $vehiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Devolver los resultados como JSON
    echo json_encode($vehiculos);
    
} catch (PDOException $e) {
    // Manejo de errores
    echo json_encode(['success' => false, 'message' => 'Error en la consulta: ' . $e->getMessage()]);
}

// Cerrar la conexión a la base de datos
$conn = null;
?>
