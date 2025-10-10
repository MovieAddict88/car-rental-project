<?php
require_once 'includes/header.php';

$action = $_GET['action'] ?? 'list';
$booking_id = $_GET['id'] ?? null;
$status = $_GET['status'] ?? null;
$error = '';
$success = '';

// Handle status updates
if ($action === 'update_status' && $booking_id && $status) {
    $allowed_statuses = ['approved', 'rejected', 'completed', 'cancelled'];
    if (in_array($status, $allowed_statuses)) {
        try {
            $stmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
            $stmt->execute([$status, $booking_id]);

            // If booking is rejected or cancelled, maybe make the car available again
            // This logic depends on business rules. For now, we'll just update status.

            $success = "Booking status updated to '$status' successfully!";
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    } else {
        $error = "Invalid status provided.";
    }
    $action = 'list'; // Go back to the list view
}
?>

<h1 class="h2">Manage Bookings</h1>

<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h5>All Bookings</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>User</th>
                        <th>Car</th>
                        <th>Dates</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Booked On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try {
                        $stmt = $pdo->query(
                            "SELECT b.*, u.name as user_name, c.brand, c.model
                             FROM bookings b
                             JOIN users u ON b.user_id = u.id
                             JOIN cars c ON b.car_id = c.id
                             ORDER BY b.created_at DESC"
                        );
                        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($bookings as $booking) {
                            $status_color = '';
                            switch ($booking['status']) {
                                case 'approved': $status_color = 'success'; break;
                                case 'pending': $status_color = 'warning'; break;
                                case 'completed': $status_color = 'info'; break;
                                case 'rejected':
                                case 'cancelled': $status_color = 'danger'; break;
                                default: $status_color = 'secondary';
                            }

                            echo '<tr>';
                            echo '<td>' . $booking['id'] . '</td>';
                            echo '<td>' . htmlspecialchars($booking['user_name']) . '</td>';
                            echo '<td>' . htmlspecialchars($booking['brand'] . ' ' . $booking['model']) . '</td>';
                            echo '<td>' . htmlspecialchars($booking['start_date']) . ' to ' . htmlspecialchars($booking['end_date']) . '</td>';
                            echo '<td>₱' . number_format($booking['total_price'], 2) . '</td>';
                            echo '<td><span class="badge bg-' . $status_color . '">' . ucfirst($booking['status']) . '</span></td>';
                            echo '<td>' . date('Y-m-d H:i', strtotime($booking['created_at'])) . '</td>';
                            echo '<td>';
                            if ($booking['status'] === 'pending') {
                                echo '<a href="manage_bookings.php?action=update_status&id=' . $booking['id'] . '&status=approved" class="btn btn-sm btn-success me-1" title="Approve"><i class="bi bi-check-lg"></i></a>';
                                echo '<a href="manage_bookings.php?action=update_status&id=' . $booking['id'] . '&status=rejected" class="btn btn-sm btn-danger" title="Reject"><i class="bi bi-x-lg"></i></a>';
                            } elseif ($booking['status'] === 'approved') {
                                 echo '<a href="manage_bookings.php?action=update_status&id=' . $booking['id'] . '&status=completed" class="btn btn-sm btn-primary me-1" title="Mark as Completed"><i class="bi bi-check-all"></i></a>';
                                 echo '<a href="manage_bookings.php?action=update_status&id=' . $booking['id'] . '&status=cancelled" class="btn btn-sm btn-warning" title="Cancel Booking"><i class="bi bi-slash-circle"></i></a>';
                            }
                            // Add a view details link maybe? For now, this is enough.
                            echo '</td>';
                            echo '</tr>';
                        }
                    } catch (PDOException $e) {
                        echo '<tr><td colspan="8" class="text-danger">Could not fetch booking data. ' . $e->getMessage() . '</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>