<?php
require_once 'db.php';
// Importar las clases de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Incluir archivos de PHPMailer (asegúrate de que esta ruta sea correcta)
require 'C:/xampp/htdocs/documentacion/PHPMailer-master/src/Exception.php';
require 'C:/xampp/htdocs/documentacion/PHPMailer-master/src/PHPMailer.php';
require 'C:/xampp/htdocs/documentacion/PHPMailer-master/src/SMTP.php';


// Conexión a la base de datos
// $host = 'localhost';
// $user = 'root';
// $pass = '';
// $db = 'fraser';

$conn = new mysqli($host, $user, $pass, $db);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta para obtener los documentos por vencer
$sql = "SELECT d.id, d.fecha_vencimiento, v.patente, c.email 
        FROM documentos d 
        JOIN vehiculos v ON d.vehiculo_id = v.id 
        JOIN clientes c ON v.cliente_id = c.id 
        WHERE d.fecha_vencimiento BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY) 
        AND d.recordatorio_enviado = 0";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Inicializar PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Servidor SMTP de Gmail
        $mail->SMTPAuth = true;
        $mail->Username = 'aristides600@gmail.com'; // Tu correo de Gmail
        $mail->Password = 'Casa2020'; // Tu contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Configuración del remitente
        $mail->setFrom('aristides600@gmail.com', 'Transporte Fraser');

        // Destinatario: aristides600@hotmail.com
        $mail->addAddress('aristides600@hotmail.com'); 

        // Recorrer documentos y enviar correos
        while ($documento = $result->fetch_assoc()) {
            $mail->clearAddresses(); // Limpiar destinatarios anteriores

            // Destinatario
            $mail->addAddress($documento['email']); // Usa el email desde la consulta SQL

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Recordatorio: Documento Próximo a Vencer';
            $mail->Body = 'Hola, el documento del vehículo con patente <strong>' . $documento['patente'] . '</strong> vencerá el <strong>' . $documento['fecha_vencimiento'] . '</strong>. Por favor, asegúrate de renovarlo a tiempo.';
            $mail->AltBody = 'Hola, el documento del vehículo con patente ' . $documento['patente'] . ' vencerá el ' . $documento['fecha_vencimiento'] . '.';

            // Enviar correo
            if ($mail->send()) {
                // Actualizar el campo recordatorio_enviado
                $update_sql = "UPDATE documentos SET recordatorio_enviado = 1 WHERE id = " . $documento['id'];
                $conn->query($update_sql);
            }
        }

        echo "Recordatorios enviados con éxito.";
    } catch (Exception $e) {
        echo "Error al enviar correos: {$mail->ErrorInfo}";
    }
} else {
    echo "No hay documentos por vencer en los próximos 7 días.";
}

$conn->close();
?>
