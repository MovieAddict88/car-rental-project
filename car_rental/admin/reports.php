<?php
require_once '../config.php';
include 'includes/header.php';

$filter_month = $_GET['month'] ?? date('Y-m');
$start_date = $filter_month . '-01';
$end_date = date("Y-m-t", strtotime($start_date));

$stmt = $pdo->prepare("
    SELECT p.*, b.user_id, u.name as user_name
    FROM payments p
    JOIN bookings b ON p.booking_id = b.id
    JOIN users u ON b.user_id = u.id
    WHERE p.status = 'completed' AND b.start_date BETWEEN ? AND ?
");
$stmt->execute([$start_date, $end_date]);
$payments = $stmt->fetchAll();

if (isset($_GET['export']) && $_GET['export'] == 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="report_'.$filter_month.'.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Payment ID', 'Booking ID', 'User', 'Amount', 'Method', 'Transaction ID', 'Status', 'Date']);
    foreach ($payments as $payment) {
        // Assuming there is a created_at in payments table, if not, this will need adjustment
        // For now, using a placeholder for date.
        fputcsv($output, [$payment['id'], $payment['booking_id'], $payment['user_name'], $payment['amount'], $payment['method'], $payment['transaction_id'], $payment['status'], date('Y-m-d')]);
    }
    fclose($output);
    exit;
}

?>

<h2>Reports</h2>
<form method="GET" class="form-inline mb-3">
    <label for="month" class="mr-2">Filter by Month:</label>
    <input type="month" id="month" name="month" class="form-control mr-2" value="<?php echo $filter_month; ?>">
    <button type="submit" class="btn btn-primary">Filter</button>
    <a href="reports.php?month=<?php echo $filter_month; ?>&export=csv" class="btn btn-secondary ml-2">Export to CSV</a>
</form>

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
                <td><?php echo $payment['status']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>