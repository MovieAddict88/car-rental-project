<?php
// This file is a placeholder for email sending functionality.
// In a real-world application, you would use a library like PHPMailer or a service like SendGrid.

/**
 * Sends a booking confirmation email to the user.
 *
 * @param string $user_email The recipient's email address.
 * @param string $user_name The recipient's name.
 * @param array $booking The booking details.
 * @return bool Returns true on success (in this simulation), false on failure.
 */
function send_booking_confirmation_email($user_email, $user_name, $booking) {
    // In a real implementation, you would use a library like PHPMailer.
    // Example using PHPMailer (this code is for demonstration and will not run here):
    /*

    require 'path/to/PHPMailer/src/Exception.php';
    require 'path/to/PHPMailer/src/PHPMailer.php';
    require 'path/to/PHPMailer/src/SMTP.php';

    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.example.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'user@example.com';
        $mail->Password   = 'secret';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        //Recipients
        $mail->setFrom('no-reply@crms.com', 'Car Rental System');
        $mail->addAddress($user_email, $user_name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your Booking Confirmation #' . $booking['id'];
        $mail->Body    = "<h1>Booking Confirmed!</h1>"
                       . "<p>Hello " . htmlspecialchars($user_name) . ",</p>"
                       . "<p>Your car rental booking has been confirmed. Here are the details:</p>"
                       . "<ul>"
                       . "<li><strong>Booking ID:</strong> " . $booking['id'] . "</li>"
                       . "<li><strong>Car:</strong> " . htmlspecialchars($booking['brand'] . ' ' . $booking['model']) . "</li>"
                       . "<li><strong>Start Date:</strong> " . $booking['start_date'] . "</li>"
                       . "<li><strong>End Date:</strong> " . $booking['end_date'] . "</li>"
                       . "<li><strong>Total Price:</strong> " . format_currency((float)$booking['total_price']) . "</li>"
                       . "</ul>"
                       . "<p>Thank you for choosing our service!</p>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        // In a real app, you would log this error.
        // error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }

    */

    // For this project, we'll just simulate a successful email send.
    // You can check server logs or a dedicated mail log to "see" the simulated email.
    error_log("Simulating booking confirmation email to " . $user_email . " for booking #" . $booking['id']);

    return true;
}
?>