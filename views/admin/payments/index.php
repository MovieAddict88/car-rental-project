<?php require_once APPROOT . '/views/admin/includes/header.php'; ?>

<h1 class="h3 mb-4 text-gray-800">Manage Payments</h1>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>User</th>
                        <th>Booking ID</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Payment Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['payments'] as $payment): ?>
                    <tr>
                        <td><?php echo $payment->transaction_id; ?></td>
                        <td><?php echo $payment->user_name; ?></td>
                        <td><?php echo $payment->booking_id; ?></td>
                        <td>$<?php echo number_format($payment->amount, 2); ?></td>
                        <td><?php echo $payment->method; ?></td>
                        <td><span class="badge bg-success"><?php echo $payment->status; ?></span></td>
                        <td><?php echo date('d M Y H:i', strtotime($payment->payment_date)); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once APPROOT . '/views/admin/includes/footer.php'; ?>