<?php
require_once '../includes/header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Save the intended destination and redirect to login
    $redirect_url = URL_ROOT . $_SERVER['REQUEST_URI'];
    header("Location: " . URL_ROOT . "/user/login.php?redirect=" . urlencode($redirect_url));
    exit();
}

// Check for car_id
if (!isset($_GET['car_id']) || !filter_var($_GET['car_id'], FILTER_VALIDATE_INT)) {
    echo '<div class="alert alert-danger">Invalid car selection.</div>';
    require_once '../includes/footer.php';
    exit();
}

$car_id = $_GET['car_id'];
$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Fetch car details
try {
    $stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ? AND availability = 1");
    $stmt->execute([$car_id]);
    $car = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$car) {
        echo '<div class="alert alert-danger">This car is not available for booking.</div>';
        require_once '../includes/footer.php';
        exit();
    }
} catch (PDOException $e) {
    echo '<div class="alert alert-danger">Database error fetching car details.</div>';
    require_once '../includes/footer.php';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Basic validation
    if (empty($start_date) || empty($end_date)) {
        $error = 'Please select both a start and end date.';
    } elseif ($start_date >= $end_date) {
        $error = 'End date must be after the start date.';
    } elseif (new DateTime($start_date) < new DateTime('today')) {
        $error = 'Start date cannot be in the past.';
    } else {
        // Calculate total price
        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        $days = $end->diff($start)->format("%a");

        // If the diff is 0, it means it's a one-day rental.
        if ($days == 0) {
            $days = 1;
        }

        $total_price = $days * $car['price_per_day'];

        // Insert booking with 'pending' status
        try {
            $stmt = $pdo->prepare("INSERT INTO bookings (user_id, car_id, start_date, end_date, total_price, status) VALUES (?, ?, ?, ?, ?, 'pending')");

            if ($stmt->execute([$user_id, $car_id, $start_date, $end_date, $total_price])) {
                $booking_id = $pdo->lastInsertId();
                // Redirect to the payment gateway to complete the process
                header("Location: " . URL_ROOT . "/payment_gateway.php?booking_id=" . $booking_id);
                exit();
            } else {
                $error = 'Failed to create booking. Please try again.';
            }
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}

?>

<div class="row">
    <div class="col-md-7">
        <h2>Book: <?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></h2>
        <img src="<?php echo URL_ROOT; ?>/assets/images/placeholder.png" class="img-fluid rounded mb-4" alt="Car Image">
        <p><strong>Type:</strong> <?php echo htmlspecialchars($car['type']); ?></p>
        <p><strong>Price per day:</strong> ₱<?php echo htmlspecialchars(number_format($car['price_per_day'], 2)); ?></p>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Select Booking Dates</h4>
                <hr>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php else: ?>
                <form action="book_car.php?car_id=<?php echo $car_id; ?>" method="post">
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" min="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Confirm Booking</button>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<?php require_once '../includes/footer.php'; ?>