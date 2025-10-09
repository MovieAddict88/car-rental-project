<?php
require_once '../config.php';
require_once '../classes/Booking.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$booking_obj = new Booking($pdo);
$bookings = $booking_obj->findByUserId($_SESSION['user_id']);

include '../includes/header.php';
?>

<h2>My Bookings</h2>
<table class="table table-bordered">
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
                <td><?php echo $booking['id']; ?></td>
                <td><?php echo $booking['brand'] . ' ' . $booking['model']; ?></td>
                <td><?php echo $booking['start_date']; ?></td>
                <td><?php echo $booking['end_date']; ?></td>
                <td>$<?php echo $booking['total_price']; ?></td>
                <td><?php echo ucfirst($booking['status']); ?></td>
                <td>
                    <?php if ($booking['status'] == 'approved'): ?>
                        <a href="invoice.php?booking_id=<?php echo $booking['id']; ?>" class="btn btn-info btn-sm">View Invoice</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>