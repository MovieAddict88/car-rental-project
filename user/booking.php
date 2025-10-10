<?php
$page_title = "Book a Car";
require_once '../includes/header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Store the intended destination to redirect after login
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header("Location: " . SITE_URL . "/user/login.php?message=login_required");
    exit();
}

// Check if a car ID is provided
if (!isset($_GET['car_id'])) {
    header("Location: " . SITE_URL . "/public/cars.php");
    exit();
}

$car_id = (int)$_GET['car_id'];

// --- Fetch car data from database ---
$stmt = $conn->prepare("SELECT * FROM cars WHERE id = ? AND availability = 'available'");
$stmt->bind_param("i", $car_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Car not found or is unavailable.");
}
$car = $result->fetch_assoc();
$stmt->close();

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    // Pickup location is not in the DB schema for bookings, so we'll ignore it for now
    // $pickup_location = trim($_POST['pickup_location']);

    // --- Validation ---
    if (empty($start_date) || empty($end_date)) {
        $errors[] = "Start and end dates are required.";
    } elseif (strtotime($end_date) <= strtotime($start_date)) {
        $errors[] = "End date must be after the start date.";
    } else {
        // Calculate total price
        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        $days = $end->diff($start)->format("%a");
        $total_price = $days * $car['price_per_day'];

        // --- Insert booking into database ---
        $stmt = $conn->prepare("INSERT INTO bookings (user_id, car_id, start_date, end_date, total_price, status) VALUES (?, ?, ?, ?, ?, 'pending')");
        $stmt->bind_param("iissd", $_SESSION['user_id'], $car_id, $start_date, $end_date, $total_price);

        if ($stmt->execute()) {
            $booking_id = $stmt->insert_id;
            // Redirect to payment gateway
            header("Location: " . SITE_URL . "/payment_gateway.php?booking_id=" . $booking_id);
            exit();
        } else {
            $errors[] = "There was an error creating your booking. Please try again.";
        }
        $stmt->close();
    }
}
?>

<div class="row">
    <!-- Car Details Summary -->
    <div class="col-lg-5">
        <div class="card">
            <img src="<?php echo $car['image']; ?>" class="card-img-top" alt="<?php echo $car['brand'] . ' ' . $car['model']; ?>">
            <div class="card-body">
                <h3><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></h3>
                <p class="text-muted"><?php echo htmlspecialchars($car['type']); ?></p>
                <p class="fs-4"><strong>$<?php echo number_format($car['price_per_day'], 2); ?></strong> / day</p>
            </div>
        </div>
    </div>

    <!-- Booking Form -->
    <div class="col-lg-7">
        <h2>Booking Details</h2>
        <hr>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): echo "<p class='mb-0'>$error</p>"; endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="booking.php?car_id=<?php echo $car_id; ?>" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" required min="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" required min="<?php echo date('Y-m-d'); ?>">
                </div>
            </div>
            <div class="mb-3">
                <label for="pickup_location" class="form-label">Pickup Location</label>
                <select class="form-select" id="pickup_location" name="pickup_location" required>
                    <option value="">Select a location</option>
                    <option value="Airport">Airport</option>
                    <option value="Downtown Office">Downtown Office</option>
                    <option value="Uptown Office">Uptown Office</option>
                </select>
            </div>
            <hr>
            <div class="text-center bg-light p-3 rounded mb-4">
                <h4>Total Price: <span id="total_price">$0.00</span></h4>
                <small class="text-muted">Calculated based on the selected dates.</small>
            </div>
            <button type="submit" class="btn btn-primary btn-lg w-100">Proceed to Payment</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const totalPriceElement = document.getElementById('total_price');
    const pricePerDay = <?php echo $car['price_per_day']; ?>;

    function calculateTotal() {
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);

        if (startDateInput.value && endDateInput.value && endDate > startDate) {
            const timeDiff = endDate.getTime() - startDate.getTime();
            const dayDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));

            if (dayDiff > 0) {
                const total = dayDiff * pricePerDay;
                totalPriceElement.textContent = '$' + total.toFixed(2);
            } else {
                 totalPriceElement.textContent = '$0.00';
            }
        } else {
            totalPriceElement.textContent = '$0.00';
        }
    }

    startDateInput.addEventListener('change', calculateTotal);
    endDateInput.addEventListener('change', calculateTotal);

    // Set min date for end date input
    startDateInput.addEventListener('change', function() {
        endDateInput.min = this.value;
    });
});
</script>

<?php
require_once '../includes/footer.php';
?>