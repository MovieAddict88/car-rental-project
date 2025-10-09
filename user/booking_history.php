<?php
include('../includes/header.php');

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user's bookings
$stmt = $pdo->prepare(
    "SELECT b.*, c.brand, c.model
     FROM bookings b
     JOIN cars c ON b.car_id = c.id
     WHERE b.user_id = ?
     ORDER BY b.created_at DESC"
);
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>My Bookings</h2>
<p>Here is a list of all your past and current bookings.</p>

<div class="card">
    <div class="card-body">
        <?php if (count($bookings) > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Car</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Total Price</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($booking['id']); ?></td>
                                <td><?php echo htmlspecialchars($booking['brand'] . ' ' . $booking['model']); ?></td>
                                <td><?php echo htmlspecialchars($booking['start_date']); ?></td>
                                <td><?php echo htmlspecialchars($booking['end_date']); ?></td>
                                <td>$<?php echo htmlspecialchars($booking['total_price']); ?></td>
                                <td>
                                    <span class="badge
                                        <?php
                                            switch ($booking['status']) {
                                                case 'Confirmed': echo 'bg-success'; break;
                                                case 'Pending': echo 'bg-warning'; break;
                                                case 'Cancelled': echo 'bg-danger'; break;
                                                case 'Completed': echo 'bg-info'; break;
                                            }
                                        ?>">
                                        <?php echo htmlspecialchars($booking['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($booking['status'] === 'Confirmed' || $booking['status'] === 'Completed'): ?>
                                        <a href="booking_receipt.php?booking_id=<?php echo $booking['id']; ?>" class="btn btn-sm btn-primary">View Receipt</a>
                                    <?php elseif ($booking['status'] === 'Pending'): ?>
                                        <a href="../payment_gateway.php?booking_id=<?php echo $booking['id']; ?>" class="btn btn-sm btn-success">Pay Now</a>
                                    <?php else: ?>
                                        <span>N/A</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>You have not made any bookings yet. <a href="../public/cars.php">Browse our cars</a> to get started.</p>
        <?php endif; ?>
    </div>
</div>

<?php include('../includes/footer.php'); ?>