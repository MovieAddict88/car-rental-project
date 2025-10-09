<?php
include('includes/header.php');

// Fetch all payments with associated user and car for context
$stmt = $pdo->query(
    "SELECT p.*, b.user_id, u.name as user_name, c.brand, c.model
     FROM payments p
     JOIN bookings b ON p.booking_id = b.id
     JOIN users u ON b.user_id = u.id
     JOIN cars c ON b.car_id = c.id
     ORDER BY p.payment_date DESC"
);
$payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h1 class="mt-4">Manage Payments</h1>
<div class="card mb-4">
    <div class="card-header">All Payment Transactions</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Payment ID</th>
                        <th>Booking ID</th>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Transaction ID</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $payment): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($payment['id']); ?></td>
                        <td><a href="manage_bookings.php">#<?php echo htmlspecialchars($payment['booking_id']); ?></a></td>
                        <td><?php echo htmlspecialchars($payment['user_name']); ?></td>
                        <td>$<?php echo htmlspecialchars(number_format($payment['amount'], 2)); ?></td>
                        <td><?php echo htmlspecialchars($payment['method']); ?></td>
                        <td><?php echo htmlspecialchars($payment['transaction_id']); ?></td>
                        <td>
                            <span class="badge <?php
                                switch ($payment['status']) {
                                    case 'Completed': echo 'bg-success'; break;
                                    case 'Pending': echo 'bg-warning'; break;
                                    case 'Failed': echo 'bg-danger'; break;
                                }
                            ?>">
                                <?php echo htmlspecialchars($payment['status']); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($payment['payment_date']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>