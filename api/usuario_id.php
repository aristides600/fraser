<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Obtener usuario_id de la URL
    $usuario_id = $_GET['usuario_id'];

    // Consulta SQL para obtener un usuario por ID
    $sql = "SELECT u.id, u.dni, u.apellido, u.nombre, u.usuario, u.clave, u.rol_id, r.id AS rol_id, r.nombre AS rol_nombre 
        FROM usuarios u 
        JOIN roles r ON u.rol_id = r.id 
        WHERE u.id = $usuario_id";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo json_encode(["error" => "Error al obtener el usuarios"]);
        exit();
    }

    $usuario = mysqli_fetch_assoc($result);
    echo json_encode($usuario);
}


?>