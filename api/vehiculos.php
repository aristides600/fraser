<?php
// vehiculos.php
require_once 'db.php';
header('Content-Type: application/json');

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $sql = "SELECT v.*, m.nombre AS marca, c.nombre AS color, mo.nombre AS modelo 
                FROM vehiculos v 
                JOIN marcas m ON v.marca_id = m.id 
                JOIN colores c ON v.color_id = c.id 
                JOIN modelos mo ON v.modelo_id = mo.id";
        $result = $conn->query($sql);

        $vehiculos = [];
        while($row = $result->fetch_assoc()) {
            $vehiculos[] = $row;
        }
        echo json_encode($vehiculos);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);

        $patente = $data['patente'];
        $marca_id = $data['marca_id'];
        $color_id = $data['color_id'];
        $motor = $data['motor'];
        $modelo_id = $data['modelo_id'];
        $anio = $data['anio'];
        $corroceria = $data['corroceria'];
        $estado = $data['estado'];

        $sql = "INSERT INTO vehiculos (patente, marca_id, color_id, motor, modelo_id, anio, corroceria, estado) 
                VALUES ('$patente', $marca_id, $color_id, '$motor', $modelo_id, $anio, '$corroceria', $estado)";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["success" => true, "message" => "Vehículo agregado exitosamente"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error: " . $sql . "<br>" . $conn->error]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);

        $id = $data['id'];
        $patente = $data['patente'];
        $marca_id = $data['marca_id'];
        $color_id = $data['color_id'];
        $motor = $data['motor'];
        $modelo_id = $data['modelo_id'];
        $anio = $data['anio'];
        $corroceria = $data['corroceria'];
        $estado = $data['estado'];

        $sql = "UPDATE vehiculos SET patente='$patente', marca_id=$marca_id, color_id=$color_id, motor='$motor', modelo_id=$modelo_id, anio=$anio, corroceria='$corroceria', estado=$estado WHERE id=$id";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["success" => true, "message" => "Vehículo actualizado exitosamente"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error: " . $sql . "<br>" . $conn->error]);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'];

        $sql = "DELETE FROM vehiculos WHERE id=$id";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["success" => true, "message" => "Vehículo eliminado exitosamente"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error: " . $sql . "<br>" . $conn->error]);
        }
        break;
}
$conn->close();
?>
