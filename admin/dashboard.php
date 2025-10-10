<?php
require_once 'includes/header.php';

// Fetching summary data
try {
    // Total Users
    $total_users = $pdo->query("SELECT count(id) FROM users WHERE role = 'user'")->fetchColumn();
    // Total Cars
    $total_cars = $pdo->query("SELECT count(id) FROM cars")->fetchColumn();
    // Total Bookings
    $total_bookings = $pdo->query("SELECT count(id) FROM bookings")->fetchColumn();
    // Total Revenue (from completed payments)
    $total_revenue = $pdo->query("SELECT sum(amount) FROM payments WHERE status = 'completed'")->fetchColumn();

    // Fetch recent bookings
    $stmt = $pdo->query(
        "SELECT b.*, u.name as user_name, c.brand, c.model
         FROM bookings b
         JOIN users u ON b.user_id = u.id
         JOIN cars c ON b.car_id = c.id
         WHERE b.status = 'pending'
         ORDER BY b.created_at DESC
         LIMIT 5"
    );
    $recent_bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch monthly revenue for the last 6 months for the chart
    $revenue_data = $pdo->query("
        SELECT
            DATE_FORMAT(payment_date, '%Y-%m') as month,
            SUM(amount) as total
        FROM payments
        WHERE status = 'completed' AND payment_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
        GROUP BY month
        ORDER BY month ASC
    ")->fetchAll(PDO::FETCH_ASSOC);

    $chart_labels = [];
    $chart_values = [];
    foreach($revenue_data as $data) {
        $chart_labels[] = date("M Y", strtotime($data['month'] . "-01"));
        $chart_values[] = $data['total'];
    }
    $chart_labels_json = json_encode($chart_labels);
    $chart_values_json = json_encode($chart_values);


} catch (PDOException $e) {
    echo '<div class="alert alert-danger">Failed to fetch dashboard data: ' . $e->getMessage() . '</div>';
    $total_users = $total_cars = $total_bookings = $total_revenue = 0;
    $recent_bookings = [];
}
?>

<h1 class="h2">Dashboard</h1>

<!-- Summary Cards -->
<div class="row">
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-people"></i> Total Users</h5>
                <p class="card-text fs-4"><?php echo $total_users; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-car-front-fill"></i> Total Cars</h5>
                <p class="card-text fs-4"><?php echo $total_cars; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-calendar-check"></i> Total Bookings</h5>
                <p class="card-text fs-4"><?php echo $total_bookings; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-cash-stack"></i> Total Revenue</h5>
                <p class="card-text fs-4">₱<?php echo number_format($total_revenue ?? 0, 2); ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Chart -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Monthly Revenue (Last 6 Months)</h5>
    </div>
    <div class="card-body">
        <canvas id="revenueChart" width="400" height="150"></canvas>
    </div>
</div>


<!-- Recent Pending Bookings -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Recent Pending Bookings</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Car</th>
                        <th>Dates</th>
                        <th>Total Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($recent_bookings): ?>
                        <?php foreach ($recent_bookings as $booking): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($booking['user_name']); ?></td>
                                <td><?php echo htmlspecialchars($booking['brand'] . ' ' . $booking['model']); ?></td>
                                <td><?php echo htmlspecialchars($booking['start_date']); ?> to <?php echo htmlspecialchars($booking['end_date']); ?></td>
                                <td>₱<?php echo number_format($booking['total_price'], 2); ?></td>
                                <td><a href="manage_bookings.php?action=view&id=<?php echo $booking['id']; ?>" class="btn btn-sm btn-primary">Review</a></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No pending bookings.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Script to pass data to Chart.js -->
<script>
    const chartLabels = <?php echo $chart_labels_json; ?>;
    const chartValues = <?php echo $chart_values_json; ?>;
</script>

<?php require_once 'includes/footer.php'; ?>