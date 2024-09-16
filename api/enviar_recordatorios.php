<?php
// Incluir la conexión a la base de datos desde db.php
require_once 'db.php';

// Importar las clases de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Incluir archivos de PHPMailer (asegúrate de que esta ruta sea correcta)
require 'C:/xampp/htdocs/documentacion/PHPMailer-master/src/Exception.php';
require 'C:/xampp/htdocs/documentacion/PHPMailer-master/src/PHPMailer.php';
require 'C:/xampp/htdocs/documentacion/PHPMailer-master/src/SMTP.php';

// Consulta para obtener los documentos por vencer
$sql = "SELECT d.id, d.fecha_vencimiento, v.patente 
        FROM documentos d 
        JOIN vehiculos v ON d.vehiculo_id = v.id 
        WHERE d.fecha_vencimiento BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY) 
        AND d.recordatorio_enviado = 0";

$stmt = $conn->prepare($sql);
$stmt->execute();
$documentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($documentos) > 0) {
    // Inicializar PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Servidor SMTP de Gmail
        $mail->SMTPAuth = true;
        $mail->Username = 'aristides600@gmail.com'; // Tu correo de Gmail
        $mail->Password = 'zslzncoswbcbzqae'; // Tu contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Encriptación STARTTLS
        $mail->Port = 587; // Puerto SMTP para STARTTLS

        // Opciones para manejar certificados SSL
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // Configuración del remitente
        $mail->setFrom('aristides600@gmail.com', 'Transporte Fraser');

        // Enviar el correo siempre a esta dirección
        $mail->addAddress('transporterg@fraser.com.ar'); // Cambia aquí tu correo destinatario

        // Recorrer documentos y enviar correos
        foreach ($documentos as $documento) {
            // Contenido del correo
            $mail->isHTML(true); // Establecer que el correo sea en formato HTML
            $mail->Subject = 'Recordatorio: Documento Próximo a Vencer';
            $mail->Body = 'Hola, el documento del vehículo con patente <strong>' . $documento['patente'] . '</strong> vencerá el <strong>' . $documento['fecha_vencimiento'] . '</strong>. Por favor, asegúrate de renovarlo a tiempo.';
            $mail->AltBody = 'Hola, el documento del vehículo con patente ' . $documento['patente'] . ' vencerá el ' . $documento['fecha_vencimiento'] . '.';

            // Intentar enviar el correo
            if ($mail->send()) {
                // Actualizar el campo recordatorio_enviado
                $update_sql = "UPDATE documentos SET recordatorio_enviado = 1 WHERE id = :id";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->execute([':id' => $documento['id']]);
                echo "Documento ID: " . $documento['id'] . " actualizado correctamente.<br>";
            } else {
                echo "Error al enviar correo para el documento ID: " . $documento['id'] . " - " . $mail->ErrorInfo . "<br>";
            }
        }

        echo "Proceso completado.";
    } catch (Exception $e) {
        echo "Error al enviar correos: {$mail->ErrorInfo}";
    }
} else {
    echo "No hay documentos por vencer en los próximos 7 días.";
}
