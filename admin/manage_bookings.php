<?php
$page_title = "Manage Bookings";
require_once 'includes/header.php';
require_once 'includes/sidebar.php';
require_once 'includes/navbar.php';

// --- Handle Booking Status Update ---
if (isset($_GET['action']) && isset($_GET['id'])) {
    $booking_id = (int)$_GET['id'];
    $action = $_GET['action'];
    $allowed_statuses = ['pending', 'confirmed', 'cancelled', 'completed'];

    if (in_array($action, $allowed_statuses)) {
        $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $action, $booking_id);
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Booking status updated successfully.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error updating booking status.</div>";
        }
        $stmt->close();
    }
}


// --- Fetch all bookings with user and car details ---
$sql = "SELECT
            b.id, b.start_date, b.end_date, b.total_price, b.status,
            u.name as user_name,
            c.brand as car_brand, c.model as car_model
        FROM bookings b
        JOIN users u ON b.user_id = u.id
        JOIN cars c ON b.car_id = c.id
        ORDER BY b.created_at DESC";
$result = $conn->query($sql);
$bookings = $result->fetch_all(MYSQLI_ASSOC);


function getStatusBadgeClass($status) {
    switch (strtolower($status)) {
        case 'confirmed': return 'bg-success';
        case 'pending': return 'bg-warning text-dark';
        case 'completed': return 'bg-info text-dark';
        case 'cancelled': return 'bg-danger';
        default: return 'bg-secondary';
    }
}
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Manage Bookings</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Bookings</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Car</th>
                            <th>Period</th>
                            <th>Total Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td>#<?php echo $booking['id']; ?></td>
                                <td><?php echo htmlspecialchars($booking['user_name']); ?></td>
                                <td><?php echo htmlspecialchars($booking['car_brand'] . ' ' . $booking['car_model']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($booking['start_date'])) . ' to ' . date('M d, Y', strtotime($booking['end_date'])); ?></td>
                                <td>$<?php echo number_format($booking['total_price'], 2); ?></td>
                                <td>
                                    <span class="badge <?php echo getStatusBadgeClass($booking['status']); ?>">
                                        <?php echo htmlspecialchars($booking['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton<?php echo $booking['id']; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton<?php echo $booking['id']; ?>">
                                            <li><a class="dropdown-item" href="manage_bookings.php?action=confirmed&id=<?php echo $booking['id']; ?>">Confirm</a></li>
                                            <li><a class="dropdown-item" href="manage_bookings.php?action=completed&id=<?php echo $booking['id']; ?>">Mark as Completed</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="manage_bookings.php?action=cancelled&id=<?php echo $booking['id']; ?>" onclick="return confirm('Are you sure you want to cancel this booking?');">Cancel Booking</a></li>
                                            <li><a class="dropdown-item" href="../invoice_generator.php?booking_id=<?php echo $booking['id']; ?>" target="_blank">View Invoice</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>