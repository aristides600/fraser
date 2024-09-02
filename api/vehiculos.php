<?php
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
        while ($row = $result->fetch_assoc()) {
            $vehiculos[] = $row;
        }
        echo json_encode($vehiculos);
        break;

    case 'POST':
        date_default_timezone_set('America/Argentina/Buenos_Aires');

        $data = json_decode(file_get_contents("php://input"), true);

        $patente = $data['patente'];
        $fecha_alta = date('Y-m-d');  // Asegura que la fecha esté formateada correctamente
        $marca_id = $data['marca_id'];
        $color_id = $data['color_id'];
        $motor = $data['motor'];
        $modelo_id = $data['modelo_id'];
        $anio = $data['anio'];
        $corroceria = $data['corroceria'];
        $estado = 1;

        // Verificar si la patente ya existe
        $sql_check = "SELECT id FROM vehiculos WHERE patente = '$patente'";
        $result_check = $conn->query($sql_check);

        if ($result_check->num_rows > 0) {
            echo json_encode(["success" => false, "message" => "Error: La patente ya existe en otro vehículo."]);
        } else {
            $sql = "INSERT INTO vehiculos (patente, fecha_alta, marca_id, color_id, motor, modelo_id, anio, corroceria, estado) 
                    VALUES ('$patente', '$fecha_alta', $marca_id, $color_id, '$motor', $modelo_id, $anio, '$corroceria', $estado)";

            if ($conn->query($sql) === TRUE) {
                echo json_encode(["success" => true, "message" => "Vehículo agregado exitosamente"]);
            } else {
                echo json_encode(["success" => false, "message" => "Error al agregar vehículo: " . $conn->error]);
            }
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

        // Verificar si la patente ya existe en otro vehículo
        $sql_check = "SELECT id FROM vehiculos WHERE patente = '$patente' AND id != $id";
        $result_check = $conn->query($sql_check);

        if ($result_check->num_rows > 0) {
            echo json_encode(["success" => false, "message" => "Error: La patente ya existe en otro vehículo."]);
        } else {
            $sql = "UPDATE vehiculos SET patente='$patente', marca_id=$marca_id, color_id=$color_id, motor='$motor', modelo_id=$modelo_id, anio=$anio, corroceria='$corroceria' WHERE id=$id";

            if ($conn->query($sql) === TRUE) {
                echo json_encode(["success" => true, "message" => "Vehículo actualizado exitosamente"]);
            } else {
                echo json_encode(["success" => false, "message" => "Error al actualizar vehículo: " . $conn->error]);
            }
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'];

        $sql = "UPDATE vehiculos SET estado = 0 WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["success" => true, "message" => "Vehículo marcado como eliminado exitosamente"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error al eliminar vehículo: " . $conn->error]);
        }
        break;
}

$conn->close();
?>
