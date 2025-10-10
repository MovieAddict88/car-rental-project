<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once APPROOT . '/assets/vendor/PHPMailer/src/Exception.php';
require_once APPROOT . '/assets/vendor/PHPMailer/src/PHPMailer.php';
require_once APPROOT . '/assets/vendor/PHPMailer/src/SMTP.php';

function sendEmail($to, $subject, $body){
    // Instantiate Setting model to get email config
    // This is a procedural file, so we can't use $this->model()
    $settingsModel = new Setting();
    $settings = $settingsModel->getSettings();

    $mail = new PHPMailer(true);

    try {
        // Server settings from database
        $mail->isSMTP();
        $mail->Host       = $settings['smtp_host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $settings['smtp_user'];
        $mail->Password   = $settings['smtp_pass'];
        $mail->SMTPSecure = $settings['smtp_secure'];
        $mail->Port       = $settings['smtp_port'];

        // Recipients
        $fromEmail = $settings['contact_email'] ?? 'no-reply@crms.com';
        $fromName = $settings['website_name'] ?? 'CRMS';

        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = strip_tags($body);

        $mail->send();
        return true;
    } catch (Exception $e) {
        // For debugging in a real environment: error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
?>