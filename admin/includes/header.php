<?php
require_once('../config/config.php');

// Security check: Ensure user is logged in and is an admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - CRMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/admin_style.css">
</head>
<body>

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <div id="sidebar-wrapper">
        <div class="sidebar-heading"><strong>CRMS Admin</strong></div>
        <div class="list-group list-group-flush">
            <?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
            <a href="index.php" class="list-group-item list-group-item-action <?php echo ($currentPage == 'index.php') ? 'active' : ''; ?>">Dashboard</a>
            <a href="manage_cars.php" class="list-group-item list-group-item-action <?php echo ($currentPage == 'manage_cars.php') ? 'active' : ''; ?>">Manage Cars</a>
            <a href="manage_bookings.php" class="list-group-item list-group-item-action <?php echo ($currentPage == 'manage_bookings.php') ? 'active' : ''; ?>">Manage Bookings</a>
            <a href="manage_users.php" class="list-group-item list-group-item-action <?php echo ($currentPage == 'manage_users.php') ? 'active' : ''; ?>">Manage Users</a>
            <a href="manage_payments.php" class="list-group-item list-group-item-action <?php echo ($currentPage == 'manage_payments.php') ? 'active' : ''; ?>">Manage Payments</a>
            <a href="reports.php" class="list-group-item list-group-item-action <?php echo ($currentPage == 'reports.php') ? 'active' : ''; ?>">Reports</a>
            <a href="settings.php" class="list-group-item list-group-item-action <?php echo ($currentPage == 'settings.php') ? 'active' : ''; ?>">Settings</a>
            <a href="../user/logout.php" class="list-group-item list-group-item-action mt-auto">Logout</a>
        </div>
    </div>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" id="menu-toggle">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="../user/logout.php">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid p-4">