<?php
include('../includes/header.php');

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect_to=" . urlencode($_SERVER['REQUEST_URI']));
    exit();
}

// Check for car_id
if (!isset($_GET['car_id'])) {
    header("Location: ../public/cars.php");
    exit();
}

$car_id = $_GET['car_id'];
$user_id = $_SESSION['user_id'];
$message = '';

// Fetch car details
$stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ? AND availability = 1");
$stmt->execute([$car_id]);
$car = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$car) {
    $message = '<div class="alert alert-danger">This car is not available or does not exist.</div>';
}

// Handle booking form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $car) {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Basic validation
    if (empty($start_date) || empty($end_date) || $start_date >= $end_date) {
        $message = '<div class="alert alert-danger">Please select a valid date range.</div>';
    } else {
        $date1 = new DateTime($start_date);
        $date2 = new DateTime($end_date);
        $interval = $date1->diff($date2);
        $num_days = $interval->days;

        if ($num_days <= 0) {
            $message = '<div class="alert alert-danger">The end date must be after the start date.</div>';
        } else {
            $total_price = $num_days * $car['price_per_day'];

            // Insert booking into the database
            $stmt = $pdo->prepare("INSERT INTO bookings (user_id, car_id, start_date, end_date, total_price, status) VALUES (?, ?, ?, ?, ?, 'Pending')");

            if ($stmt->execute([$user_id, $car_id, $start_date, $end_date, $total_price])) {
                $booking_id = $pdo->lastInsertId();
                // For simplicity, we'll redirect to a confirmation page. In a real app, this would go to payment.
                header("Location: booking_confirmation.php?booking_id=" . $booking_id);
                exit();
            } else {
                $message = '<div class="alert alert-danger">Failed to create booking. Please try again.</div>';
            }
        }
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <h2>Book a Car</h2>
        <?php echo $message; ?>

        <?php if ($car): ?>
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <img src="<?php echo BASE_URL; ?>/assets/images/<?php echo htmlspecialchars($car['image']); ?>" class="img-fluid" alt="Car Image">
                    </div>
                    <div class="col-md-8">
                        <h4><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></h4>
                        <p><strong>Type:</strong> <?php echo htmlspecialchars($car['type']); ?></p>
                        <p><strong>Price:</strong> <?php echo htmlspecialchars(format_currency((float)$car['price_per_day'])); ?> / day</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4>Select Booking Dates</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Proceed to Confirmation</button>
                </form>
            </div>
        </div>
        <?php else: ?>
            <a href="../public/cars.php" class="btn btn-secondary">Back to Car Listings</a>
        <?php endif; ?>
    </div>
</div>

<?php include('../includes/footer.php'); ?>