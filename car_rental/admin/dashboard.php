<?php
include 'includes/header.php';

// Fetch stats
$total_users = $pdo->query("SELECT count(*) FROM users")->fetchColumn();
$total_cars = $pdo->query("SELECT count(*) FROM cars")->fetchColumn();
$total_bookings = $pdo->query("SELECT count(*) FROM bookings")->fetchColumn();
$total_revenue = $pdo->query("SELECT sum(amount) FROM payments WHERE status = 'completed'")->fetchColumn();
?>

<h2>Dashboard</h2>
<div class="row">
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3">
            <div class="card-header">Total Users</div>
            <div class="card-body">
                <h5 class="card-title"><?php echo $total_users; ?></h5>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3">
            <div class="card-header">Total Cars</div>
            <div class="card-body">
                <h5 class="card-title"><?php echo $total_cars; ?></h5>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info mb-3">
            <div class="card-header">Total Bookings</div>
            <div class="card-body">
                <h5 class="card-title"><?php echo $total_bookings; ?></h5>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning mb-3">
            <div class="card-header">Total Revenue</div>
            <div class="card-body">
                <h5 class="card-title">$<?php echo number_format($total_revenue, 2); ?></h5>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>