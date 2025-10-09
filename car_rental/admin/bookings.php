<?php
require_once '../config.php';
include 'includes/header.php';

if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = $_GET['id'];
    $stmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
    $stmt->execute([$action, $id]);
    header("Location: bookings.php");
    exit;
}

$bookings = $pdo->query("
    SELECT b.*, u.name as user_name, c.brand, c.model
    FROM bookings b
    JOIN users u ON b.user_id = u.id
    JOIN cars c ON b.car_id = c.id
    ORDER BY b.id DESC
")->fetchAll();
?>

<h2>Manage Bookings</h2>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Car</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Total Price</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($bookings as $booking): ?>
            <tr>
                <td><?php echo $booking['id']; ?></td>
                <td><?php echo $booking['user_name']; ?></td>
                <td><?php echo $booking['brand'] . ' ' . $booking['model']; ?></td>
                <td><?php echo $booking['start_date']; ?></td>
                <td><?php echo $booking['end_date']; ?></td>
                <td>$<?php echo $booking['total_price']; ?></td>
                <td><?php echo ucfirst($booking['status']); ?></td>
                <td>
                    <?php if ($booking['status'] == 'pending'): ?>
                        <a href="bookings.php?action=approved&id=<?php echo $booking['id']; ?>" class="btn btn-success btn-sm">Approve</a>
                        <a href="bookings.php?action=cancelled&id=<?php echo $booking['id']; ?>" class="btn btn-danger btn-sm">Cancel</a>
                    <?php elseif ($booking['status'] == 'approved'): ?>
                        <a href="bookings.php?action=completed&id=<?php echo $booking['id']; ?>" class="btn btn-info btn-sm">Mark as Completed</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>