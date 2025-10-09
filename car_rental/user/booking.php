<?php
require_once '../config.php';
require_once '../classes/Car.php';
require_once '../classes/Booking.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$car_id = $_GET['car_id'];
$car_obj = new Car($pdo);
$car = $car_obj->findById($car_id);

if (!$car) {
    die("Car not found.");
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $date1 = new DateTime($start_date);
    $date2 = new DateTime($end_date);
    $interval = $date1->diff($date2);
    $days = $interval->days;

    if ($days <= 0) {
        $error = "End date must be after start date.";
    } else {
        $total_price = $days * $car['price_per_day'];

        $booking = new Booking($pdo);
        $booking_id = $booking->create($_SESSION['user_id'], $car_id, $start_date, $end_date, $total_price);

        if ($booking_id) {
            header("Location: payment.php?booking_id=" . $booking_id);
            exit;
        } else {
            $error = "Failed to create booking.";
        }
    }
}

include '../includes/header.php';
?>

<h2>Book Car: <?php echo $car['brand'] . ' ' . $car['model']; ?></h2>
<div class="row">
    <div class="col-md-6">
        <img src="../images/<?php echo $car['image']; ?>" class="img-fluid" alt="<?php echo $car['brand'] . ' ' . $car['model']; ?>">
        <p>Price: $<?php echo $car['price_per_day']; ?>/day</p>
    </div>
    <div class="col-md-6">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input type="date" name="start_date" id="start_date" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="end_date">End Date</label>
                <input type="date" name="end_date" id="end_date" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Confirm Booking</button>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>