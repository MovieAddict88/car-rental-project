<?php
require_once('../includes/header.php');

// Check if the user is logged in and is an admin, otherwise redirect
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== 'admin') {
    // If not logged in, redirect to login page
    if (!isset($_SESSION["user_id"])) {
        header("location: ../user/login.php");
    } else {
        // If logged in but not an admin, redirect to user profile or homepage
        header("location: ../user/profile.php");
    }
    exit;
}
?>

<h1 class="mb-4">Admin Dashboard</h1>
<p>Welcome, <strong><?php echo htmlspecialchars($_SESSION["user_name"]); ?></strong>! You have access to the admin panel.</p>

<div class="row">
    <!-- Summary Cards -->
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3">
            <div class="card-header">Total Cars</div>
            <div class="card-body">
                <h5 class="card-title">150</h5>
                <p class="card-text">Available and booked</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3">
            <div class="card-header">Total Bookings</div>
            <div class="card-body">
                <h5 class="card-title">45</h5>
                <p class="card-text">Pending and confirmed</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info mb-3">
            <div class="card-header">Total Users</div>
            <div class="card-body">
                <h5 class="card-title">1200</h5>
                <p class="card-text">Registered users</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning mb-3">
            <div class="card-header">Revenue</div>
            <div class="card-body">
                <h5 class="card-title">$12,500</h5>
                <p class="card-text">This month</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        Quick Actions
    </div>
    <div class="card-body">
        <a href="#" class="btn btn-primary">Manage Cars</a>
        <a href="#" class="btn btn-secondary">Manage Bookings</a>
        <a href="#" class="btn btn-info">Manage Users</a>
        <a href="#" class="btn btn-success">View Reports</a>
    </div>
</div>

<?php require_once('../includes/footer.php'); ?>