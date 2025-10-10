<?php
require_once '../includes/header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: " . URL_ROOT . "/user/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    // Fetch all bookings for the user
    $stmt = $pdo->prepare(
        "SELECT b.*, c.brand, c.model, c.image, p.status as payment_status
         FROM bookings b
         JOIN cars c ON b.car_id = c.id
         LEFT JOIN payments p ON b.id = p.booking_id
         WHERE b.user_id = ?
         ORDER BY b.created_at DESC"
    );
    $stmt->execute([$user_id]);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $bookings = [];
    echo '<div class="alert alert-danger">Error fetching booking history.</div>';
}

function get_status_badge($status) {
    switch (strtolower($status)) {
        case 'approved':
            return 'bg-success';
        case 'pending':
            return 'bg-warning';
        case 'completed':
            return 'bg-info';
        case 'cancelled':
        case 'rejected':
            return 'bg-danger';
        default:
            return 'bg-secondary';
    }
}
?>

<div class="row">
    <div class="col-md-3">
        <div class="list-group">
            <a href="dashboard.php" class="list-group-item list-group-item-action">Dashboard</a>
            <a href="booking_history.php" class="list-group-item list-group-item-action active">Booking History</a>
            <a href="profile.php" class="list-group-item list-group-item-action">Profile</a>
            <a href="logout.php" class="list-group-item list-group-item-action">Logout</a>
        </div>
    </div>
    <div class="col-md-9">
        <h2>My Booking History</h2>
        <hr>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Car</th>
                        <th>Dates</th>
                        <th>Total Price</th>
                        <th>Booking Status</th>
                        <th>Payment Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($bookings): ?>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($booking['brand'] . ' ' . $booking['model']); ?></td>
                                <td><?php echo htmlspecialchars($booking['start_date']); ?> to <?php echo htmlspecialchars($booking['end_date']); ?></td>
                                <td>₱<?php echo htmlspecialchars(number_format($booking['total_price'], 2)); ?></td>
                                <td><span class="badge <?php echo get_status_badge($booking['status']); ?>"><?php echo ucfirst(htmlspecialchars($booking['status'])); ?></span></td>
                                <td><span class="badge <?php echo $booking['payment_status'] === 'completed' ? 'bg-success' : 'bg-warning'; ?>"><?php echo ucfirst(htmlspecialchars($booking['payment_status'] ?? 'pending')); ?></span></td>
                                <td>
                                    <?php if ($booking['status'] == 'approved' && $booking['payment_status'] == 'completed'): ?>
                                        <a href="<?php echo URL_ROOT; ?>/invoice_generator.php?booking_id=<?php echo $booking['id']; ?>" class="btn btn-sm btn-info" target="_blank">Download Invoice</a>
                                    <?php elseif ($booking['status'] == 'pending'): ?>
                                        <a href="<?php echo URL_ROOT; ?>/payment_gateway.php?booking_id=<?php echo $booking['id']; ?>" class="btn btn-sm btn-success">Pay Now</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">You have not made any bookings yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>