<?php
include('../includes/header.php');

// Redirect if not logged in or no booking ID
if (!isset($_SESSION['user_id']) || !isset($_GET['booking_id'])) {
    header("Location: login.php");
    exit();
}

$booking_id = $_GET['booking_id'];
$user_id = $_SESSION['user_id'];

// Fetch confirmed booking details along with payment info
$stmt = $pdo->prepare(
    "SELECT b.*, c.brand, c.model, c.image, p.transaction_id, p.payment_date, p.method
     FROM bookings b
     JOIN cars c ON b.car_id = c.id
     JOIN payments p ON b.id = p.booking_id
     WHERE b.id = ? AND b.user_id = ? AND b.status = 'Confirmed' AND p.status = 'Completed'"
);
$stmt->execute([$booking_id, $user_id]);
$receipt = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$receipt) {
    echo "<div class='alert alert-danger'>Receipt not found or booking is not confirmed.</div>";
    include('../includes/footer.php');
    exit();
}
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <h2>Booking Receipt</h2>
        <div class="alert alert-success">Your booking has been successfully confirmed and payment is complete.</div>

        <div class="card">
            <div class="card-header">
                <h4>Receipt #<?php echo htmlspecialchars($receipt['id']); ?></h4>
            </div>
            <div class="card-body">
                <h5>Booking Details</h5>
                <p><strong>Car:</strong> <?php echo htmlspecialchars($receipt['brand'] . ' ' . $receipt['model']); ?></p>
                <p><strong>Rental Period:</strong> <?php echo htmlspecialchars($receipt['start_date']); ?> to <?php echo htmlspecialchars($receipt['end_date']); ?></p>

                <hr>

                <h5>Payment Details</h5>
                <p><strong>Transaction ID:</strong> <?php echo htmlspecialchars($receipt['transaction_id']); ?></p>
                <p><strong>Payment Date:</strong> <?php echo htmlspecialchars($receipt['payment_date']); ?></p>
                <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($receipt['method']); ?></p>
                <h4 class="text-success"><strong>Total Paid: $<?php echo htmlspecialchars($receipt['total_price']); ?></strong></h4>
            </div>
            <div class="card-footer text-center">
                <a href="../invoice_generator.php?booking_id=<?php echo $receipt['id']; ?>" class="btn btn-primary" target="_blank">Download PDF Invoice</a>
                <a href="booking_history.php" class="btn btn-secondary">View All Bookings</a>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>