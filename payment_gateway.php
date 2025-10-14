<?php
require_once('config/config.php');

require_once('includes/email_functions.php');

// Redirect if not logged in or no booking ID is provided
if (!isset($_SESSION['user_id']) || !isset($_GET['booking_id'])) {
    header("Location: " . BASE_URL . "/user/login.php");
    exit();
}

$booking_id = $_GET['booking_id'];
$user_id = $_SESSION['user_id'];
$message = '';

// Fetch booking details to ensure it belongs to the current user
$stmt = $pdo->prepare("SELECT b.*, c.brand, c.model, u.name as user_name, u.email as user_email FROM bookings b JOIN cars c ON b.car_id = c.id JOIN users u ON b.user_id = u.id WHERE b.id = ? AND b.user_id = ?");
$stmt->execute([$booking_id, $user_id]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    // Redirect if booking not found or doesn't belong to the user
    header("Location: " . BASE_URL . "/user/booking_history.php");
    exit();
}

// Check if the booking is already confirmed or paid
if ($booking['status'] !== 'Pending') {
    $message = '<div class="alert alert-warning">This booking has already been processed.</div>';
}

// Handle Payment Simulation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_payment']) && $booking['status'] === 'Pending') {
    $payment_method = 'Simulated Gateway'; // In a real app, this would be 'Stripe', 'PayPal', etc.
    $transaction_id = 'SIM_' . time() . '_' . $booking_id; // Simulated transaction ID
    $amount = $booking['total_price'];
    $payment_status = 'Completed'; // Simulate a successful payment

    // Step 1: Insert payment record
    $stmt = $pdo->prepare("INSERT INTO payments (booking_id, amount, method, transaction_id, status) VALUES (?, ?, ?, ?, ?)");

    if ($stmt->execute([$booking_id, $amount, $payment_method, $transaction_id, $payment_status])) {
        // Step 2: Update booking status to 'Confirmed'
        $stmt = $pdo->prepare("UPDATE bookings SET status = 'Confirmed' WHERE id = ?");

        if ($stmt->execute([$booking_id])) {
            // Step 3: Update car availability
            $stmt = $pdo->prepare("UPDATE cars SET availability = 0 WHERE id = ?");
            $stmt->execute([$booking['car_id']]);

            // Step 4: Send confirmation email
            send_booking_confirmation_email($booking['user_email'], $booking['user_name'], $booking);

            // Redirect to a final confirmation/receipt page
            header("Location: " . BASE_URL . "/user/booking_receipt.php?booking_id=" . $booking_id);
            exit();
        } else {
            $message = '<div class="alert alert-danger">Error updating booking status.</div>';
        }
    } else {
        $message = '<div class="alert alert-danger">Error processing payment. Please try again.</div>';
    }
}
?>

<?php include('includes/header.php'); ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <h2>Payment Simulation</h2>
        <?php echo $message; ?>

        <?php if ($booking && $booking['status'] === 'Pending'): ?>
        <div class="card">
            <div class="card-header">
                <h4>Confirm Your Booking</h4>
            </div>
            <div class="card-body">
                <h5>Booking Summary</h5>
                <p><strong>Car:</strong> <?php echo htmlspecialchars($booking['brand'] . ' ' . $booking['model']); ?></p>
                <p><strong>Start Date:</strong> <?php echo htmlspecialchars($booking['start_date']); ?></p>
                <p><strong>End Date:</strong> <?php echo htmlspecialchars($booking['end_date']); ?></p>
                <h4 class="text-success"><strong>Total Amount: <?php echo htmlspecialchars(format_currency((float)$booking['total_price'])); ?></strong></h4>
                <hr>

                <!--
                    REAL PAYMENT GATEWAY INTEGRATION:
                    - Replace this form with the Stripe Elements or PayPal button.
                    - On successful payment, you would receive a transaction ID from the payment provider.
                    - The server-side logic would then handle creating the payment record and updating the booking status.
                    - For example, with Stripe, you would use their PHP library to create a charge or confirm a PaymentIntent.
                -->

                <form method="POST">
                    <p>This is a simulated payment gateway. No real payment will be processed.</p>
                    <button type="submit" name="confirm_payment" class="btn btn-success w-100">Confirm Payment</button>
                </form>
            </div>
        </div>
        <?php else: ?>
            <a href="<?php echo BASE_URL; ?>/user/booking_history.php" class="btn btn-primary">View My Bookings</a>
        <?php endif; ?>
    </div>
</div>

<?php include('includes/footer.php'); ?>