<?php
$page_title = "My Bookings";
require_once '../includes/header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: " . SITE_URL . "/user/login.php");
    exit();
}

// --- Handle Booking Cancellation ---
if (isset($_GET['action']) && $_GET['action'] == 'cancel' && isset($_GET['id'])) {
    $booking_id = (int)$_GET['id'];

    // First, verify the booking belongs to the user and get car_id
    $stmt = $conn->prepare("SELECT car_id, status FROM bookings WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $booking_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $booking = $result->fetch_assoc();
        // Only allow cancellation if booking is pending or confirmed
        if ($booking['status'] == 'pending' || $booking['status'] == 'confirmed') {
            $conn->begin_transaction();
            try {
                // Update booking status
                $stmt_cancel = $conn->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = ?");
                $stmt_cancel->bind_param("i", $booking_id);
                $stmt_cancel->execute();

                // If booking was confirmed, make the car available again
                if ($booking['status'] == 'confirmed') {
                    $stmt_car = $conn->prepare("UPDATE cars SET availability = 'available' WHERE id = ?");
                    $stmt_car->bind_param("i", $booking['car_id']);
                    $stmt_car->execute();
                }

                $conn->commit();
                echo "<div class='alert alert-success'>Booking #$booking_id has been cancelled.</div>";
            } catch (mysqli_sql_exception $exception) {
                $conn->rollback();
                echo "<div class='alert alert-danger'>Error cancelling booking.</div>";
            }
        }
    }
    $stmt->close();
}

// --- Fetch user's bookings from the database ---
$user_id = $_SESSION['user_id'];
$sql = "SELECT b.id, c.brand as car_brand, c.model as car_model, b.start_date, b.end_date, b.total_price, b.status
        FROM bookings b
        JOIN cars c ON b.car_id = c.id
        WHERE b.user_id = ?
        ORDER BY b.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$bookings = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

function getStatusBadgeClass($status) {
    switch (strtolower($status)) {
        case 'confirmed':
            return 'bg-success';
        case 'pending':
            return 'bg-warning text-dark';
        case 'completed':
            return 'bg-info text-dark';
        case 'cancelled':
            return 'bg-danger';
        default:
            return 'bg-secondary';
    }
}
?>

<div class="row">
    <!-- Sidebar Navigation -->
    <div class="col-lg-3">
        <div class="card">
            <div class="card-header">
                <h5>User Menu</h5>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><a href="profile.php" class="text-decoration-none text-dark">My Profile</a></li>
                <li class="list-group-item"><a href="bookings.php" class="text-decoration-none text-dark active">My Bookings</a></li>
                <li class="list-group-item"><a href="logout.php" class="text-decoration-none text-danger">Logout</a></li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="col-lg-9">
        <h2>My Booking History</h2>
        <hr>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Booking ID</th>
                                <th>Car</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Total Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($bookings)): ?>
                                <tr>
                                    <td colspan="7" class="text-center">You have no bookings yet. <a href="../public/cars.php">Find a car to rent</a>.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($bookings as $booking): ?>
                                    <tr>
                                        <td>#<?php echo $booking['id']; ?></td>
                                        <td><?php echo htmlspecialchars($booking['car_brand'] . ' ' . $booking['car_model']); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($booking['start_date'])); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($booking['end_date'])); ?></td>
                                        <td>$<?php echo number_format($booking['total_price'], 2); ?></td>
                                        <td>
                                            <span class="badge <?php echo getStatusBadgeClass($booking['status']); ?>">
                                                <?php echo htmlspecialchars($booking['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="../invoice_generator.php?booking_id=<?php echo $booking['id']; ?>" class="btn btn-sm btn-info" title="View Invoice">
                                                <i class="fas fa-file-invoice"></i>
                                            </a>
                                            <?php if (in_array(strtolower($booking['status']), ['pending', 'confirmed'])): ?>
                                                <a href="bookings.php?action=cancel&id=<?php echo $booking['id']; ?>" class="btn btn-sm btn-danger" title="Cancel Booking" onclick="return confirm('Are you sure you want to cancel this booking?');">
                                                    <i class="fas fa-times-circle"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once '../includes/footer.php';
?>