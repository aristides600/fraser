<?php
require_once 'db.php';

// Obtener la marca_id de la URL
$marca_id = $_GET['marca_id'];

// Consulta SQL para obtener los modelos de una marca
$sql = "SELECT * FROM modelos WHERE marca_id = '$marca_id'";
$result = $conn->query($sql);

$modelos = [];

while ($row = $result->fetch_assoc()) {
    $modelos[] = $row;
}

echo json_encode($modelos);

// Cerrar la conexión
$conn->close();