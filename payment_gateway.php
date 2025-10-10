<?php
$page_title = "Complete Your Payment";
require_once 'includes/header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: " . SITE_URL . "/user/login.php?message=payment_login_required");
    exit();
}

// Check if a booking ID is provided
if (!isset($_GET['booking_id'])) {
    header("Location: " . SITE_URL . "/public/cars.php?message=booking_not_found");
    exit();
}

$booking_id = (int)$_GET['booking_id'];

// --- Fetch booking data from database ---
$stmt = $conn->prepare(
    "SELECT b.*, c.brand as car_brand, c.model as car_model
     FROM bookings b
     JOIN cars c ON b.car_id = c.id
     WHERE b.id = ? AND b.user_id = ?"
);
$stmt->bind_param("ii", $booking_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Booking not found or you do not have permission to view it.");
}
$booking = $result->fetch_assoc();
$stmt->close();

// --- Handle Payment Submission ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- Payment Simulation ---
    // In a real application, you would integrate with a payment gateway here.
    // For now, we'll simulate a successful payment.

    $payment_method = 'Stripe'; // Or 'PayPal', etc.
    $transaction_id = 'txn_' . uniqid();
    $payment_status = 'completed';

    $conn->begin_transaction();
    try {
        // 1. Update booking status to 'confirmed'
        $stmt_booking = $conn->prepare("UPDATE bookings SET status = 'confirmed' WHERE id = ?");
        $stmt_booking->bind_param("i", $booking_id);
        $stmt_booking->execute();
        $stmt_booking->close();

        // 2. Create a record in the 'payments' table
        $stmt_payment = $conn->prepare("INSERT INTO payments (booking_id, amount, method, transaction_id, status) VALUES (?, ?, ?, ?, ?)");
        $stmt_payment->bind_param("idsss", $booking_id, $booking['total_price'], $payment_method, $transaction_id, $payment_status);
        $stmt_payment->execute();
        $stmt_payment->close();

        // 3. Update car availability to 'booked'
        $stmt_car = $conn->prepare("UPDATE cars SET availability = 'booked' WHERE id = ?");
        $stmt_car->bind_param("i", $booking['car_id']);
        $stmt_car->execute();
        $stmt_car->close();

        $conn->commit();

        // Redirect to success page
        header("Location: " . SITE_URL . "/payment_success.php?booking_id=" . $booking_id);
        exit();

    } catch (mysqli_sql_exception $exception) {
        $conn->rollback();
        // You would log the error here
        die("Transaction failed. Please try again.");
    }
}

?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <h2 class="text-center mb-4">Secure Payment</h2>
        <div class="card shadow">
            <div class="card-body">
                <div class="row">
                    <!-- Order Summary -->
                    <div class="col-md-6 bg-light p-4 rounded">
                        <h4>Booking Summary</h4>
                        <hr>
                        <p><strong>Car:</strong> <?php echo htmlspecialchars($booking['car_brand'] . ' ' . $booking['car_model']); ?></p>
                        <p><strong>From:</strong> <?php echo date('M d, Y', strtotime($booking['start_date'])); ?></p>
                        <p><strong>To:</strong> <?php echo date('M d, Y', strtotime($booking['end_date'])); ?></p>
                        <hr>
                        <h4 class="d-flex justify-content-between">
                            <span>Total Amount:</span>
                            <strong>$<?php echo number_format($booking['total_price'], 2); ?></strong>
                        </h4>
                    </div>

                    <!-- Payment Form -->
                    <div class="col-md-6 p-4">
                        <h4>Enter Payment Details</h4>
                        <hr>
                        <form action="payment_gateway.php?booking_id=<?php echo $booking_id; ?>" method="POST" id="payment-form">
                            <div class="mb-3">
                                <label for="card_name" class="form-label">Name on Card</label>
                                <input type="text" class="form-control" id="card_name" placeholder="John Doe" required>
                            </div>
                            <div class="mb-3">
                                <label for="card_number" class="form-label">Card Number</label>
                                <input type="text" class="form-control" id="card_number" placeholder="1234 5678 9012 3456" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="expiry_date" class="form-label">Expiry Date</label>
                                    <input type="text" class="form-control" id="expiry_date" placeholder="MM / YY" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="cvc" class="form-label">CVC</label>
                                    <input type="text" class="form-control" id="cvc" placeholder="123" required>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Pay $<?php echo number_format($booking['total_price'], 2); ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>