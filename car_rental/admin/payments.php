<?php
require_once '../config.php';
include 'includes/header.php';

$payments = $pdo->query("
    SELECT p.*, u.name as user_name
    FROM payments p
    JOIN bookings b ON p.booking_id = b.id
    JOIN users u ON b.user_id = u.id
    ORDER BY p.id DESC
")->fetchAll();
?>

<h2>Manage Payments</h2>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Booking ID</th>
            <th>User</th>
            <th>Amount</th>
            <th>Method</th>
            <th>Transaction ID</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($payments as $payment): ?>
            <tr>
                <td><?php echo $payment['id']; ?></td>
                <td><?php echo $payment['booking_id']; ?></td>
                <td><?php echo $payment['user_name']; ?></td>
                <td>$<?php echo $payment['amount']; ?></td>
                <td><?php echo $payment['method']; ?></td>
                <td><?php echo $payment['transaction_id']; ?></td>
                <td><?php echo ucfirst($payment['status']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>