<?php
require_once 'db.php';
header('Content-Type: application/json');

// Obtener la solicitud
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

// Operaciones CRUD
switch ($method) {
    case 'GET':
        // Obtener todos los vehiculos
        $result = $conn->query("SELECT v.*, m.nombre AS nombre_marca, mo.nombre AS nombre_modelo,
         c.nombre AS nombre_color, cl.apellido AS cliente_apellido, cl.nombre AS cliente_nombre
        FROM vehiculos v
        JOIN marcas m ON v.marca_id = m.id
        JOIN modelos mo ON v.modelo_id = mo.id
        JOIN colores c ON v.color_id = c.id
        JOIN clientes cl ON v.cliente_id = cl.id
        WHERE v.estado = true
        ORDER BY v.id ASC");

        $vehiculos = [];

        while ($row = $result->fetch_assoc()) {
            $vehiculos[] = $row;
        }
        echo json_encode($vehiculos);
        break;

    case 'POST':

        $usuario_id = $_SESSION['user_id'];
        // Escapar los valores para prevenir inyección SQL
        $patente = strtoupper($conn->real_escape_string($input['patente']));
        $marca_id = $conn->real_escape_string($input['marca_id']);
        $modelo_id = $conn->real_escape_string($input['modelo_id']);
        $color_id = $conn->real_escape_string($input['color_id']);
        $ano = $conn->real_escape_string($input['ano']);
        $carroceria = strtoupper($conn->real_escape_string($input['carroceria']));
        $motor = strtoupper($conn->real_escape_string($input['motor']));
        $cliente_id = $conn->real_escape_string($input['cliente_id']);
        $estado = 1;

        // Insertar el nuevo vehículo en la base de datos
        $sql = "INSERT INTO vehiculos (ano, carroceria, color_id, marca_id, modelo_id, motor, patente, cliente_id, estado) 
                    VALUES ('$ano', '$carroceria', '$color_id', '$marca_id', '$modelo_id', '$motor', '$patente', '$cliente_id', '$estado')";

        if ($conn->query($sql) === TRUE) {
            // Si la inserción fue exitosa, devolver un mensaje de éxito
            $data['message'] = 'Vehículo agregado correctamente';
        } else {
            // Si hubo un error en la inserción, devolver un mensaje de error
            $data['error'] = 'Error al agregar vehículo: ' . $conn->error;
        }

        break;

    case 'PUT':
        // Obtener el ID del vehículo a actualizar
        $id = $conn->real_escape_string($input['id']);

        // Escapar los valores para prevenir inyección SQL
        $patente = strtoupper($conn->real_escape_string($input['patente']));
        $marca_id = $conn->real_escape_string($input['marca_id']);
        $modelo_id = $conn->real_escape_string($input['modelo_id']);
        $color_id = $conn->real_escape_string($input['color_id']);
        $cliente_id = $conn->real_escape_string($input['cliente_id']);

        $ano = $conn->real_escape_string($input['ano']);
        $carroceria = strtoupper($conn->real_escape_string($input['carroceria']));
        $motor = strtoupper($conn->real_escape_string($input['motor']));

        // Actualizar el vehículo en la base de datos
        $sql = "UPDATE vehiculos SET patente = '$patente', marca_id = '$marca_id', modelo_id = '$modelo_id', color_id = '$color_id',  cliente_id = '$cliente_id', ano = '$ano', carroceria = '$carroceria', motor = '$motor' WHERE id = '$id'";
        if ($conn->query($sql) === TRUE) {
            // Si la actualización fue exitosa, devolver un mensaje de éxito
            $data['message'] = 'Vehículo actualizado correctamente';
        } else {
            // Si hubo un error en la actualización, devolver un mensaje de error
            $data['error'] = 'Error al actualizar vehículo: ' . $conn->error;
        }
        break;
    case 'DELETE':

        $id = $conn->real_escape_string($input['id']);
        $sql = "UPDATE vehiculos SET estado = false WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(['success' => true, 'message' => 'Vehiculo eliminado con éxito.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el vehiculo.']);
        }
        break;
}

// Cerrar la conexión
$conn->close();
