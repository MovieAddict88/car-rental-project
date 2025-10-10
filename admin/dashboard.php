<?php
$page_title = "Dashboard";

// Include Admin Header
require_once 'includes/header.php';

// Include Admin Sidebar
require_once 'includes/sidebar.php';

// Include Admin Navbar
require_once 'includes/navbar.php';

// --- Fetch dynamic data from the database ---

// Total Cars
$result_cars = $conn->query("SELECT COUNT(*) as count FROM cars");
$total_cars = $result_cars->fetch_assoc()['count'];

// Total Users
$result_users = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'user'");
$total_users = $result_users->fetch_assoc()['count'];

// Total Bookings
$result_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings");
$total_bookings = $result_bookings->fetch_assoc()['count'];

// Total Revenue
$result_revenue = $conn->query("SELECT SUM(amount) as total FROM payments WHERE status = 'completed'");
$total_revenue = $result_revenue->fetch_assoc()['total'] ?? 0;
?>

<!-- Main Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Admin Dashboard</h1>
        <a href="manage_cars.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add New Car
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="row">
        <!-- Total Cars Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Cars</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_cars; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-car fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Users Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_users; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Bookings Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Bookings</div>
                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $total_bookings; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Revenue Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">$<?php echo number_format($total_revenue); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links & Recent Activity Section -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <a href="manage_bookings.php" class="btn btn-light btn-icon-split mb-2">
                        <span class="icon text-gray-600"><i class="fas fa-check"></i></span>
                        <span class="text">Approve Bookings</span>
                    </a>
                    <a href="manage_users.php" class="btn btn-light btn-icon-split mb-2">
                        <span class="icon text-gray-600"><i class="fas fa-user-plus"></i></span>
                        <span class="text">Manage Users</span>
                    </a>
                     <a href="reports.php" class="btn btn-light btn-icon-split mb-2">
                        <span class="icon text-gray-600"><i class="fas fa-chart-area"></i></span>
                        <span class="text">View Reports</span>
                    </a>
                    <a href="settings.php" class="btn btn-light btn-icon-split mb-2">
                        <span class="icon text-gray-600"><i class="fas fa-cogs"></i></span>
                        <span class="text">System Settings</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
             <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">System Status</h6>
                </div>
                <div class="card-body">
                    <p>Welcome to the CRMS Admin Panel. From here you can manage all aspects of your car rental business.</p>
                    <p>Use the sidebar to navigate to different sections.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Include Admin Footer
require_once 'includes/footer.php';
?>