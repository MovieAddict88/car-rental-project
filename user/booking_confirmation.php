<?php
include('../includes/header.php');

// Redirect if not logged in or no booking ID
if (!isset($_SESSION['user_id']) || !isset($_GET['booking_id'])) {
    header("Location: login.php");
    exit();
}

$booking_id = $_GET['booking_id'];
$user_id = $_SESSION['user_id'];

// Fetch booking details to confirm it belongs to the user
$stmt = $pdo->prepare(
    "SELECT b.*, c.brand, c.model, c.price_per_day, c.image
     FROM bookings b
     JOIN cars c ON b.car_id = c.id
     WHERE b.id = ? AND b.user_id = ? AND b.status = 'Pending'"
);
$stmt->execute([$booking_id, $user_id]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    echo "<div class='alert alert-danger'>Booking not found or already processed.</div>";
    include('../includes/footer.php');
    exit();
}

// Calculate number of days for display
$date1 = new DateTime($booking['start_date']);
$date2 = new DateTime($booking['end_date']);
$interval = $date1->diff($date2);
$num_days = $interval->days;
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <h2>Booking Confirmation</h2>
        <div class="alert alert-info">Please review your booking details below and proceed to payment.</div>

        <div class="card">
            <div class="card-header">
                <h4>Booking Details</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5">
                        <img src="<?php echo BASE_URL; ?>/assets/images/<?php echo htmlspecialchars($booking['image']); ?>" class="img-fluid rounded" alt="Car Image">
                    </div>
                    <div class="col-md-7">
                        <h5><strong>Car:</strong> <?php echo htmlspecialchars($booking['brand'] . ' ' . $booking['model']); ?></h5>
                        <p><strong>Rental Period:</strong> <?php echo htmlspecialchars($booking['start_date']); ?> to <?php echo htmlspecialchars($booking['end_date']); ?></p>
                        <p><strong>Duration:</strong> <?php echo $num_days; ?> day(s)</p>
                        <p><strong>Price per day:</strong> $<?php echo htmlspecialchars($booking['price_per_day']); ?></p>
                        <hr>
                        <h4 class="text-primary"><strong>Total Amount: $<?php echo htmlspecialchars($booking['total_price']); ?></strong></h4>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="../payment_gateway.php?booking_id=<?php echo $booking['id']; ?>" class="btn btn-success btn-lg">Proceed to Payment</a>
                <a href="../public/cars.php" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>