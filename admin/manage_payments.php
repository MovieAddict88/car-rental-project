<?php
$page_title = "Manage Payments";
require_once 'includes/header.php';
require_once 'includes/sidebar.php';
require_once 'includes/navbar.php';

// --- Fetch all payments from the database ---
$result = $conn->query("SELECT * FROM payments ORDER BY payment_date DESC");
$payments = $result->fetch_all(MYSQLI_ASSOC);

function getPaymentStatusBadgeClass($status) {
    switch (strtolower($status)) {
        case 'completed': return 'bg-success';
        case 'pending': return 'bg-warning text-dark';
        case 'failed': return 'bg-danger';
        default: return 'bg-secondary';
    }
}
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Manage Payments</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Payment History</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Payment ID</th>
                            <th>Booking ID</th>
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
                                <td>#<?php echo $payment['id']; ?></td>
                                <td><a href="manage_bookings.php?id=<?php echo $payment['booking_id']; ?>">#<?php echo $payment['booking_id']; ?></a></td>
                                <td>$<?php echo number_format($payment['amount'], 2); ?></td>
                                <td><?php echo htmlspecialchars($payment['method']); ?></td>
                                <td><?php echo htmlspecialchars($payment['transaction_id'] ?? 'N/A'); ?></td>
                                <td>
                                    <span class="badge <?php echo getPaymentStatusBadgeClass($payment['status']); ?>">
                                        <?php echo ucfirst($payment['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($payment['payment_date'])); ?></td>
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