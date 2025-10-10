<?php
require_once 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . URL_ROOT . "/user/login.php");
    exit();
}

if (!isset($_GET['booking_id']) || !filter_var($_GET['booking_id'], FILTER_VALIDATE_INT)) {
    echo '<div class="alert alert-danger">Invalid booking ID.</div>';
    require_once 'includes/footer.php';
    exit();
}

$booking_id = $_GET['booking_id'];

// In a real application, you would integrate with PayPal, Stripe, etc. here.
// For this project, we'll simulate a successful payment.

try {
    // Fetch booking to get the amount
    $stmt = $pdo->prepare("SELECT total_price FROM bookings WHERE id = ? AND user_id = ? AND status = 'pending'");
    $stmt->execute([$booking_id, $_SESSION['user_id']]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($booking) {
        $total_price = $booking['total_price'];

        // Start a transaction
        $pdo->beginTransaction();

        // 1. Create a payment record
        $stmt_payment = $pdo->prepare("INSERT INTO payments (booking_id, amount, method, transaction_id, status) VALUES (?, ?, ?, ?, 'completed')");
        $transaction_id = 'SIM_' . uniqid(); // Simulated transaction ID
        $stmt_payment->execute([$booking_id, $total_price, 'Simulated Gateway', $transaction_id]);

        // 2. Update the booking status to 'approved'
        $stmt_booking = $pdo->prepare("UPDATE bookings SET status = 'approved' WHERE id = ?");
        $stmt_booking->execute([$booking_id]);

        // Commit the transaction
        $pdo->commit();

        echo '<div class="alert alert-success">Payment successful! Your booking is confirmed. You will be redirected to your booking history.</div>';
        header("refresh:3;url=" . URL_ROOT . "/user/booking_history.php");

    } else {
        echo '<div class="alert alert-warning">Booking not found or already processed.</div>';
    }

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo '<div class="alert alert-danger">Payment processing failed. Please try again. Error: ' . $e->getMessage() . '</div>';
}

require_once 'includes/footer.php';
?>