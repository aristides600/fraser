<?php
require_once 'db.php';
header('Content-Type: application/json');

$sql = "SELECT id, nombre FROM tipos";
$result = $conn->query($sql);

$tipos = [];

while ($row = $result->fetch_assoc()) {
  $tipos[] = $row;
}

echo json_encode($tipos);

$conn->close();
?>
