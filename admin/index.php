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
$total_revenue = $pdo->query("SELECT sum(amount) FROM payments WHERE status = 'Completed'")->fetchColumn();
$total_revenue = $total_revenue ? $total_revenue : 0; // Handle case where there are no payments
?>

<h1 class="mt-4">Dashboard</h1>
<p>Welcome to the admin dashboard, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>

<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card text-white mb-4 card-cars">
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
        <div class="card text-white mb-4 card-users">
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
        <div class="card text-white mb-4 card-bookings">
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
        <div class="card text-white mb-4 card-revenue">
            <div class="card-body">
                 <div class="d-flex justify-content-between">
                    <div>
                        <i class="fas fa-peso-sign fa-3x"></i>
                    </div>
                    <div>
                        <div class="fs-1 fw-bold">₱<?php echo number_format($total_revenue, 2); ?></div>
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