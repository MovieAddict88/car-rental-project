<?php
$page_title = "Payment Successful";
require_once 'includes/header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: " . SITE_URL . "/user/login.php");
    exit();
}

// Check if a booking ID is provided
if (!isset($_GET['booking_id'])) {
    header("Location: " . SITE_URL . "/user/bookings.php?message=invalid_confirmation");
    exit();
}

$booking_id = (int)$_GET['booking_id'];

// --- Fetch confirmed booking and payment details from the database ---
$stmt = $conn->prepare(
    "SELECT b.*, c.brand as car_brand, c.model as car_model, p.transaction_id, p.payment_date
     FROM bookings b
     JOIN cars c ON b.car_id = c.id
     JOIN payments p ON b.id = p.booking_id
     WHERE b.id = ? AND b.user_id = ? AND b.status = 'confirmed'"
);
$stmt->bind_param("ii", $booking_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Booking confirmation not found.");
}
$booking = $result->fetch_assoc();
$stmt->close();

// --- Send Email Notification (Placeholder) ---
// In a real app, you would integrate an email library like PHPMailer
// $to = $_SESSION['user_email'];
// $subject = "Your Booking is Confirmed! - Booking ID #" . $booking['id'];
// $message = "Dear " . $_SESSION['user_name'] . ",\n\nYour booking for the " . $booking['car_brand'] . " " . $booking['car_model'] . " is confirmed.\n...";
// mail($to, $subject, $message);

?>

<div class="row justify-content-center">
    <div class="col-lg-8 text-center">
        <div class="card shadow-lg">
            <div class="card-body p-5">
                <div class="mb-4">
                    <i class="fas fa-check-circle fa-5x text-success"></i>
                </div>
                <h1 class="card-title">Payment Successful!</h1>
                <p class="lead">Your booking has been confirmed.</p>
                <hr>
                <p>A confirmation email has been sent to <strong><?php echo htmlspecialchars($_SESSION['user_email']); ?></strong>.</p>

                <div class="text-start bg-light p-4 rounded my-4">
                    <h4 class="mb-3">Booking Summary</h4>
                    <p><strong>Booking ID:</strong> #<?php echo $booking['id']; ?></p>
                    <p><strong>Transaction ID:</strong> <?php echo htmlspecialchars($booking['transaction_id']); ?></p>
                    <p><strong>Car:</strong> <?php echo htmlspecialchars($booking['car_brand'] . ' ' . $booking['car_model']); ?></p>
                    <p><strong>Rental Period:</strong> <?php echo date('M d, Y', strtotime($booking['start_date'])); ?> to <?php echo date('M d, Y', strtotime($booking['end_date'])); ?></p>
                    <p><strong>Total Paid:</strong> $<?php echo number_format($booking['total_price'], 2); ?></p>
                </div>

                <div class="mt-4">
                    <a href="user/bookings.php" class="btn btn-primary">
                        <i class="fas fa-list-alt"></i> View My Bookings
                    </a>
                    <a href="invoice_generator.php?booking_id=<?php echo $booking_id; ?>" class="btn btn-secondary">
                        <i class="fas fa-download"></i> Download Invoice
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>