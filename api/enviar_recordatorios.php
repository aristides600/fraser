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
        // Activar el modo de depuración (opcional)
        $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Cambiar a SMTP::DEBUG_OFF para desactivar depuración
        $mail->Debugoutput = 'html'; // Output en formato HTML para depuración

        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Servidor SMTP de Gmail
        $mail->SMTPAuth = true;
        $mail->Username = 'aristides600@gmail.com'; // Tu correo de Gmail
        $mail->Password = 'jonlemfqbzivrwol'; // Tu contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Encriptación STARTTLS
        $mail->Port = 587; // Puerto SMTP para STARTTLS

        // Opciones para manejar certificados SSL (en caso de problemas con el certificado)
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // Configuración del remitente
        $mail->setFrom('aristides600@gmail.com', 'Transporte Fraser');

        // Recorrer documentos y enviar correos
        while ($documento = $result->fetch_assoc()) {
            $mail->clearAddresses(); // Limpiar destinatarios anteriores

            // Destinatario dinámico (desde la consulta SQL)
            $mail->addAddress($documento['email']); 

            // Contenido del correo
            $mail->isHTML(true); // Establecer que el correo sea en formato HTML
            $mail->Subject = 'Recordatorio: Documento Próximo a Vencer';
            $mail->Body = 'Hola, el documento del vehículo con patente <strong>' . $documento['patente'] . '</strong> vencerá el <strong>' . $documento['fecha_vencimiento'] . '</strong>. Por favor, asegúrate de renovarlo a tiempo.';
            $mail->AltBody = 'Hola, el documento del vehículo con patente ' . $documento['patente'] . ' vencerá el ' . $documento['fecha_vencimiento'] . '.';

            // Intentar enviar el correo
            if ($mail->send()) {
                // Actualizar el campo recordatorio_enviado
                $update_sql = "UPDATE documentos SET recordatorio_enviado = 1 WHERE id = " . $documento['id'];
                if ($conn->query($update_sql) === TRUE) {
                    echo "Documento ID: " . $documento['id'] . " actualizado correctamente.<br>";
                } else {
                    echo "Error al actualizar documento ID: " . $documento['id'] . ": " . $conn->error . "<br>";
                }
            } else {
                // Mostrar error de envío de correo
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

// Cerrar conexión a la base de datos
$conn->close();
?>
