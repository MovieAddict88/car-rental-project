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
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/admin_style.css">
</head>
<body>

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <div class="bg-dark border-right" id="sidebar-wrapper">
        <div class="sidebar-heading text-white p-3"><strong>CRMS Admin</strong></div>
        <div class="list-group list-group-flush">
            <a href="index.php" class="list-group-item list-group-item-action bg-dark text-white">Dashboard</a>
            <a href="manage_cars.php" class="list-group-item list-group-item-action bg-dark text-white">Manage Cars</a>
            <a href="manage_bookings.php" class="list-group-item list-group-item-action bg-dark text-white">Manage Bookings</a>
            <a href="manage_users.php" class="list-group-item list-group-item-action bg-dark text-white">Manage Users</a>
            <a href="manage_payments.php" class="list-group-item list-group-item-action bg-dark text-white">Manage Payments</a>
            <a href="reports.php" class="list-group-item list-group-item-action bg-dark text-white">Reports</a>
            <a href="settings.php" class="list-group-item list-group-item-action bg-dark text-white">Settings</a>
            <a href="../user/logout.php" class="list-group-item list-group-item-action bg-dark text-white mt-auto">Logout</a>
        </div>
    </div>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <div class="container-fluid">
                <button class="btn btn-primary" id="menu-toggle">Toggle Menu</button>
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