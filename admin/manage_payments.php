<?php
require_once 'includes/header.php';

// This page is primarily for viewing payments. Actions might be added later (e.g., refund).
?>

<h1 class="h2">Manage Payments</h1>

<div class="card">
    <div class="card-header">
        <h5>All Payment Transactions</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Payment ID</th>
                        <th>Booking ID</th>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Transaction ID</th>
                        <th>Status</th>
                        <th>Payment Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try {
                        $stmt = $pdo->query(
                            "SELECT p.*, b.user_id, u.name as user_name
                             FROM payments p
                             JOIN bookings b ON p.booking_id = b.id
                             JOIN users u ON b.user_id = u.id
                             ORDER BY p.payment_date DESC"
                        );
                        $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($payments as $payment) {
                            $status_badge = '';
                            switch ($payment['status']) {
                                case 'completed':
                                    $status_badge = 'bg-success';
                                    break;
                                case 'pending':
                                    $status_badge = 'bg-warning';
                                    break;
                                case 'failed':
                                    $status_badge = 'bg-danger';
                                    break;
                                default:
                                    $status_badge = 'bg-secondary';
                                    break;
                            }

                            echo '<tr>';
                            echo '<td>' . $payment['id'] . '</td>';
                            echo '<td><a href="manage_bookings.php?action=view&id=' . $payment['booking_id'] . '">' . $payment['booking_id'] . '</a></td>';
                            echo '<td>' . htmlspecialchars($payment['user_name']) . '</td>';
                            echo '<td>₱' . number_format($payment['amount'], 2) . '</td>';
                            echo '<td>' . htmlspecialchars($payment['method']) . '</td>';
                            echo '<td>' . htmlspecialchars($payment['transaction_id']) . '</td>';
                            echo '<td><span class="badge ' . $status_badge . '">' . ucfirst($payment['status']) . '</span></td>';
                            echo '<td>' . date('Y-m-d H:i', strtotime($payment['payment_date'])) . '</td>';
                            echo '</tr>';
                        }
                    } catch (PDOException $e) {
                        echo '<tr><td colspan="8" class="text-danger">Could not fetch payment data. ' . $e->getMessage() . '</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>