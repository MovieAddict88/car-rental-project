<?php
include('includes/header.php');

// --- Basic Reporting Logic ---
// For demonstration, we'll fetch some basic aggregate data.
// A real-world scenario would involve date filters and more complex queries.

// Total revenue
$total_revenue = $pdo->query("SELECT COALESCE(SUM(amount), 0) FROM payments WHERE status = 'Completed'")->fetchColumn();

// Total bookings
$total_bookings = $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();

// Most popular car
$popular_car_stmt = $pdo->query(
    "SELECT c.brand, c.model, COUNT(b.car_id) as booking_count
     FROM bookings b
     JOIN cars c ON b.car_id = c.id
     GROUP BY b.car_id
     ORDER BY booking_count DESC
     LIMIT 1"
);
$popular_car = $popular_car_stmt->fetch(PDO::FETCH_ASSOC);

?>

<h1 class="mt-4">Reports</h1>
<p>This section provides an overview of system activity. Advanced filtering and export options can be added here.</p>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-bar me-1"></i>
                Financial Summary
            </div>
            <div class="card-body">
                <h4>Total Revenue: <span class="text-success"><?php echo format_currency((float)($total_revenue ?? 0)); ?></span></h4>
                <p>This reflects all completed payments.</p>
                <!-- Placeholder for a chart -->
                <canvas id="revenueChart" style="display:none;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-car me-1"></i>
                Booking & Car Statistics
            </div>
            <div class="card-body">
                <h4>Total Bookings: <?php echo $total_bookings ?? 0; ?></h4>
                <?php if ($popular_car): ?>
                    <h5>Most Popular Car: <?php echo htmlspecialchars($popular_car['brand'] . ' ' . $popular_car['model']); ?></h5>
                    <p>with <?php echo $popular_car['booking_count']; ?> bookings.</p>
                <?php else: ?>
                    <p>No booking data available to determine the most popular car.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        Export Data
    </div>
    <div class="card-body">
        <form class="d-flex gap-2 flex-wrap">
            <a href="export_bookings_csv.php" class="btn btn-outline-primary">Export Bookings (CSV)</a>
            <a href="export_payments_pdf.php" class="btn btn-outline-secondary" target="_blank">Export Payments (PDF)</a>
        </form>
    </div>
</div>


<?php include('includes/footer.php'); ?>
<!-- Placeholder for a charting library like Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Example of how a chart could be initialized
// const ctx = document.getElementById('revenueChart');
// new Chart(ctx, { ... });
</script>