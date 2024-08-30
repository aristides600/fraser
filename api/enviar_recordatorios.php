<?php
// Conexión a la base de datos
require_once 'db.php';
header('Content-Type: application/json');

// Obtener la fecha de hoy y la fecha de dentro de 2 días
$hoy = date('Y-m-d');
$dos_dias_despues = date('Y-m-d', strtotime('+2 days'));

// Consulta para obtener los documentos que vencen en 2 días y cuyo estado es 1
$sql = "SELECT d.id, d.vehiculo_id, d.tipo_id, d.fecha_vencimiento, d.observacion, d.usuario_id, v.patente, v.propietario_email 
        FROM documentos d
        JOIN vehiculos v ON d.vehiculo_id = v.id
        WHERE d.estado = 1 AND d.fecha_vencimiento = '$dos_dias_despues'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $to = $row['propietario_email']; // Email del propietario del vehículo
        $subject = "Recordatorio de vencimiento de documento";
        $message = "Estimado propietario,\n\n"
            . "Le informamos que el documento del vehículo con patente " . $row['patente']
            . " está próximo a vencer el " . $row['fecha_vencimiento'] . ".\n\n"
            . "Observación: " . $row['observacion'] . "\n\n"
            . "Por favor, tome las medidas necesarias.\n\n"
            . "Atentamente,\nTu taller";

        $headers = "From: aristides600@gmail.com";

        // Enviar el correo
        if (mail($to, $subject, $message, $headers)) {
            echo "Correo enviado a: " . $to . "\n";
        } else {
            echo "Error al enviar correo a: " . $to . "\n";
        }
    }
} else {
    echo "No hay documentos próximos a vencer con estado 1.\n";
}

$conn->close();
