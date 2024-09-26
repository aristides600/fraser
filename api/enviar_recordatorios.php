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
        WHERE d.fecha_vencimiento BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 10 DAY) 
        AND d.estado = 1";

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
        $mail->setFrom('aristides600@gmail.com', 'InfoSys');

        // Lista de correos destinatarios
        $correos = [
            'aristides600@hotmail.com',
            'aristides600@gmail.com'
        ];

        // Recorrer documentos y enviar correos
        foreach ($documentos as $documento) {
            // Limpiar destinatarios antes de agregar los nuevos
            $mail->clearAddresses();

            // Agregar múltiples destinatarios
            foreach ($correos as $correo) {
                $mail->addAddress($correo);
            }

            // Configurar la zona horaria a Buenos Aires
            $argentinaTimezone = new DateTimeZone('America/Argentina/Buenos_Aires');

            // Convertir la fecha de vencimiento al formato correcto
            $fechaVencimiento = new DateTime($documento['fecha_vencimiento']);
            $fechaVencimiento->setTimezone($argentinaTimezone);
            $fechaFormateada = $fechaVencimiento->format('d/m/Y H:i:s'); // Puedes ajustar el formato de fecha como prefieras

            // Configurar UTF-8 para caracteres especiales
            $mail->CharSet = 'UTF-8';

            // Contenido del correo
            $mail->isHTML(true); // Establecer que el correo sea en formato HTML
            $mail->Subject = 'Documento por Vencer';
            $mail->Body = 'Patente <strong>' . htmlentities($documento['patente'], ENT_QUOTES, 'UTF-8') . '</strong> vencerá el <strong>' . htmlentities($fechaFormateada, ENT_QUOTES, 'UTF-8') . '</strong>.';
            $mail->AltBody = 'Hola, el documento del vehículo con patente ' . htmlentities($documento['patente'], ENT_QUOTES, 'UTF-8') . ' vencerá el ' . htmlentities($fechaFormateada, ENT_QUOTES, 'UTF-8') . '.';

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
    echo "No hay documentos por vencer en los próximos 10 días.";
}
?>
