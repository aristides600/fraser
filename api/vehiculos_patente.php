<?php
// require_once 'db.php';
// header('Content-Type: application/json');

// $patente = $_GET['patente'];

// $sql = "SELECT v.id, v.patente, m.nombre AS marca, mo.nombre AS modelo, v.anio 
//         FROM vehiculos v
//         JOIN marcas m ON v.marca_id = m.id
//         JOIN modelos mo ON v.modelo_id = mo.id
//         WHERE v.patente LIKE ?";
// $stmt = $conn->prepare($sql);
// $patente = "%$patente%";
// $stmt->bind_param("s", $patente);
// $stmt->execute();
// $result = $stmt->get_result();

// $vehiculos = [];

// while ($row = $result->fetch_assoc()) {
//   $vehiculos[] = $row;
// }

// echo json_encode($vehiculos);

// $stmt->close();
// $conn->close();

require_once 'db.php';
header('Content-Type: application/json');

$patente = $_GET['patente'];

$sql = "SELECT v.id, v.patente, c.nombre AS color, m.nombre AS marca, mo.nombre AS modelo, v.anio 
        FROM vehiculos v
        JOIN marcas m ON v.marca_id = m.id
        JOIN modelos mo ON v.modelo_id = mo.id
        JOIN colores c ON v.color_id = c.id

        WHERE v.patente LIKE ?";
$stmt = $conn->prepare($sql);
$patente = "%$patente%";
$stmt->bind_param("s", $patente);
$stmt->execute();
$result = $stmt->get_result();

$vehiculos = [];

while ($row = $result->fetch_assoc()) {
  $vehiculos[] = $row;
}

echo json_encode($vehiculos);

$stmt->close();
$conn->close();
