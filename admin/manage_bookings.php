<?php
include('includes/header.php');

$message = '';

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $booking_id = $_POST['booking_id'];
    $new_status = $_POST['status'];
    $car_id = $_POST['car_id'];

    // Begin transaction
    $pdo->beginTransaction();

    try {
        // Update booking status
        $stmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        $stmt->execute([$new_status, $booking_id]);

        // If booking is confirmed or completed, car is unavailable
        if ($new_status === 'Confirmed' || $new_status === 'Completed') {
            $stmt = $pdo->prepare("UPDATE cars SET availability = 0 WHERE id = ?");
            $stmt->execute([$car_id]);
        }
        // If booking is cancelled, car becomes available again
        elseif ($new_status === 'Cancelled') {
            $stmt = $pdo->prepare("UPDATE cars SET availability = 1 WHERE id = ?");
            $stmt->execute([$car_id]);
        }

        $pdo->commit();
        $message = '<div class="alert alert-success">Booking status updated successfully.</div>';
    } catch (Exception $e) {
        $pdo->rollBack();
        $message = '<div class="alert alert-danger">Failed to update booking status: ' . $e->getMessage() . '</div>';
    }
}

echo $message;

// Fetch all bookings with user and car details
$stmt = $pdo->query(
    "SELECT b.*, u.name as user_name, c.brand, c.model
     FROM bookings b
     JOIN users u ON b.user_id = u.id
     JOIN cars c ON b.car_id = c.id
     ORDER BY b.created_at DESC"
);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h1 class="mt-4">Manage Bookings</h1>
<div class="card mb-4">
    <div class="card-header">All Bookings</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Car</th>
                        <th>Period</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($booking['id']); ?></td>
                        <td><?php echo htmlspecialchars($booking['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($booking['brand'] . ' ' . $booking['model']); ?></td>
                        <td><?php echo htmlspecialchars($booking['start_date']); ?> to <?php echo htmlspecialchars($booking['end_date']); ?></td>
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
                            <form method="POST" class="d-inline-flex">
                                <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                <input type="hidden" name="car_id" value="<?php echo $booking['car_id']; ?>">
                                <select name="status" class="form-select form-select-sm me-2">
                                    <option value="Pending" <?php if($booking['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                    <option value="Confirmed" <?php if($booking['status'] == 'Confirmed') echo 'selected'; ?>>Confirm</option>
                                    <option value="Cancelled" <?php if($booking['status'] == 'Cancelled') echo 'selected'; ?>>Cancel</option>
                                    <option value="Completed" <?php if($booking['status'] == 'Completed') echo 'selected'; ?>>Complete</option>
                                </select>
                                <button type="submit" name="update_status" class="btn btn-primary btn-sm">Update</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>