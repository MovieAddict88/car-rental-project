<?php
require_once '../includes/header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: " . URL_ROOT . "/user/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    // Fetch user's upcoming bookings
    $stmt = $pdo->prepare("SELECT b.*, c.brand, c.model FROM bookings b JOIN cars c ON b.car_id = c.id WHERE b.user_id = ? AND b.status IN ('pending', 'approved') AND b.end_date >= CURDATE() ORDER BY b.start_date ASC");
    $stmt->execute([$user_id]);
    $upcoming_bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch user's booking history
    $stmt = $pdo->prepare("SELECT b.*, c.brand, c.model FROM bookings b JOIN cars c ON b.car_id = c.id WHERE b.user_id = ? AND (b.status NOT IN ('pending', 'approved') OR b.end_date < CURDATE()) ORDER BY b.start_date DESC");
    $stmt->execute([$user_id]);
    $booking_history = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $upcoming_bookings = [];
    $booking_history = [];
    echo '<div class="alert alert-danger">Error fetching booking data.</div>';
}

?>

<div class="row">
    <div class="col-md-3">
        <div class="list-group">
            <a href="dashboard.php" class="list-group-item list-group-item-action active">Dashboard</a>
            <a href="booking_history.php" class="list-group-item list-group-item-action">Booking History</a>
            <a href="profile.php" class="list-group-item list-group-item-action">Profile</a>
            <a href="logout.php" class="list-group-item list-group-item-action">Logout</a>
        </div>
    </div>
    <div class="col-md-9">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
        <hr>

        <h4>Upcoming Bookings</h4>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Car</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Total Price</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($upcoming_bookings): ?>
                        <?php foreach ($upcoming_bookings as $booking): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($booking['brand'] . ' ' . $booking['model']); ?></td>
                                <td><?php echo htmlspecialchars($booking['start_date']); ?></td>
                                <td><?php echo htmlspecialchars($booking['end_date']); ?></td>
                                <td>₱<?php echo htmlspecialchars(number_format($booking['total_price'], 2)); ?></td>
                                <td><span class="badge bg-<?php echo $booking['status'] == 'approved' ? 'success' : 'warning'; ?>"><?php echo ucfirst(htmlspecialchars($booking['status'])); ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">You have no upcoming bookings. <a href="<?php echo URL_ROOT; ?>/public/cars.php">Book a car now!</a></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <h4 class="mt-5">Booking History</h4>
         <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Car</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Total Price</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($booking_history): ?>
                        <?php foreach ($booking_history as $booking): ?>
                             <tr>
                                <td><?php echo htmlspecialchars($booking['brand'] . ' ' . $booking['model']); ?></td>
                                <td><?php echo htmlspecialchars($booking['start_date']); ?></td>
                                <td><?php echo htmlspecialchars($booking['end_date']); ?></td>
                                <td>₱<?php echo htmlspecialchars(number_format($booking['total_price'], 2)); ?></td>
                                <td><span class="badge bg-secondary"><?php echo ucfirst(htmlspecialchars($booking['status'])); ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No past bookings found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>