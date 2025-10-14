<?php
include('includes/header.php');

// Fetch dashboard statistics
// Total Cars
$total_cars = $pdo->query("SELECT count(*) FROM cars")->fetchColumn();

// Total Users
$total_users = $pdo->query("SELECT count(*) FROM users WHERE role = 'user'")->fetchColumn();

// Total Bookings
$total_bookings = $pdo->query("SELECT count(*) FROM bookings")->fetchColumn();

// Total Revenue
// Ensure revenue is 0 when there are no completed payments
$total_revenue = $pdo->query("SELECT COALESCE(SUM(amount), 0) FROM payments WHERE status = 'Completed'")->fetchColumn();
?>

<h1 class="mt-4">Dashboard</h1>
<p>Welcome to the admin dashboard, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>

<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card bg-primary text-white mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <i class="fas fa-car fa-3x"></i>
                    </div>
                    <div>
                        <div class="fs-1 fw-bold"><?php echo $total_cars; ?></div>
                        <div>Total Cars</div>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="manage_cars.php">View Details</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-warning text-white mb-4">
            <div class="card-body">
                 <div class="d-flex justify-content-between">
                    <div>
                        <i class="fas fa-users fa-3x"></i>
                    </div>
                    <div>
                        <div class="fs-1 fw-bold"><?php echo $total_users; ?></div>
                        <div>Total Users</div>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="manage_users.php">View Details</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-success text-white mb-4">
            <div class="card-body">
                 <div class="d-flex justify-content-between">
                    <div>
                        <i class="fas fa-book-open fa-3x"></i>
                    </div>
                    <div>
                        <div class="fs-1 fw-bold"><?php echo $total_bookings; ?></div>
                        <div>Total Bookings</div>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="manage_bookings.php">View Details</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-danger text-white mb-4">
            <div class="card-body">
                 <div class="d-flex justify-content-between">
                    <div>
                        <i class="fas fa-dollar-sign fa-3x"></i>
                    </div>
                    <div>
                        <div class="fs-1 fw-bold"><?php echo format_currency((float)($total_revenue ?? 0)); ?></div>
                        <div>Total Revenue</div>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="manage_payments.php">View Details</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
</div>

<!-- You can add charts or recent activity here later -->

<?php include('includes/footer.php'); ?>
<!-- Font Awesome for icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>