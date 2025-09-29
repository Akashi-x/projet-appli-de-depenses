<?php
// Inclure les fichiers PHPMailer
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Fonction pour envoyer un email
function sendEmail($to, $subject, $htmlBody, $textBody = '', $attachments = []) {
    // Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                       // Gmail SMTP server
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'gestion.appdep@gmail.com';             // SMTP username
        $mail->Password   = 'nkuiexszbopxzvzg';                    // SMTP password (mot de passe d'application)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;        // Enable TLS encryption
        $mail->Port       = 587;                                    // TCP port to connect to
        $mail->CharSet    = 'UTF-8';                               // Encodage des caractères

        // Recipients
        $mail->setFrom('gestion.appdep@gmail.com', 'Application Suivi Dépenses');
        $mail->addAddress($to);                                     // Add a recipient

        // Attachments (optionnel)
        if (!empty($attachments)) {
            foreach ($attachments as $attachment) {
                if (is_array($attachment)) {
                    $mail->addAttachment($attachment[0], $attachment[1] ?? '');
                } else {
                    $mail->addAttachment($attachment);
                }
            }
        }

        // Content
        $mail->isHTML(true);                                        // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $htmlBody;
        $mail->AltBody = $textBody ?: strip_tags($htmlBody);        // Plain text version

        $mail->send();
        return ['success' => true, 'message' => 'Email envoyé avec succès'];
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
        if (empty($errorMessage)) {
            $errorMessage = "Erreur inconnue lors de l'envoi de l'email";
        }
        return ['success' => false, 'message' => $errorMessage];
    }
}
?>