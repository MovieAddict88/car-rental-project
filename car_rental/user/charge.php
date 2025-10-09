<?php
require_once '../config.php';
require_once '../classes/Payment.php';
require_once '../classes/Booking.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $booking_id = $_POST['booking_id'];
    $amount = $_POST['amount'];

    $booking_obj = new Booking($pdo);
    $booking = $booking_obj->findById($booking_id);

    if (!$booking) {
        die("Booking not found.");
    }

    /*
        DEVELOPER NOTE:
        This is a simulated payment process. In a real application, you would
        process the payment with Stripe (or another gateway) here.
        If the payment is successful, you would then record the transaction
        and update the booking status as shown below.

        // Example with Stripe:
        // \Stripe\Stripe::setApiKey('YOUR_STRIPE_SECRET_KEY');
        // try {
        //     $charge = \Stripe\Charge::create([...]);
        //     // Payment successful, now record it...
        // } catch (Exception $e) {
        //     // Payment failed
        //     die("Payment failed: " . $e->getMessage());
        // }
    */

    // Simulate a successful payment
    $payment_obj = new Payment($pdo);
    // Using a simulated transaction ID
    $transaction_id = 'SIMULATED_' . uniqid();
    $payment_id = $payment_obj->create($booking_id, $amount, 'simulated_stripe', $transaction_id, 'completed');

    if ($payment_id) {
        // Update booking status to approved
        $stmt = $pdo->prepare("UPDATE bookings SET status = 'approved' WHERE id = ?");
        $stmt->execute([$booking_id]);

        // Set car to unavailable
        $stmt = $pdo->prepare("UPDATE cars SET availability = 0 WHERE id = ?");
        $stmt->execute([$booking['car_id']]);

        header("Location: bookings.php?payment=success");
        exit;
    } else {
        die("Error saving payment information.");
    }
} else {
    header("Location: index.php");
    exit;
}
?>