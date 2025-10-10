<?php
// Admin access control
if(!isAdmin()){
    redirect('public/pages/index');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - <?php echo 'CRMS'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/admin.css">
</head>
<body>
<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <div class="bg-dark border-right" id="sidebar-wrapper">
        <div class="sidebar-heading text-white">CRMS Admin</div>
        <div class="list-group list-group-flush">
            <a href="<?php echo SITE_URL; ?>/admin/dashboard" class="list-group-item list-group-item-action bg-dark text-white"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="<?php echo SITE_URL; ?>/admin/cars" class="list-group-item list-group-item-action bg-dark text-white"><i class="bi bi-car-front-fill"></i> Manage Cars</a>
            <a href="<?php echo SITE_URL; ?>/admin/bookings" class="list-group-item list-group-item-action bg-dark text-white"><i class="bi bi-calendar-check"></i> Manage Bookings</a>
            <a href="<?php echo SITE_URL; ?>/admin/users" class="list-group-item list-group-item-action bg-dark text-white"><i class="bi bi-people-fill"></i> Manage Users</a>
            <a href="<?php echo SITE_URL; ?>/admin/payments" class="list-group-item list-group-item-action bg-dark text-white"><i class="bi bi-credit-card"></i> Manage Payments</a>
            <a href="<?php echo SITE_URL; ?>/admin/reports" class="list-group-item list-group-item-action bg-dark text-white"><i class="bi bi-file-earmark-bar-graph"></i> Reports</a>
            <a href="<?php echo SITE_URL; ?>/admin/feedback" class="list-group-item list-group-item-action bg-dark text-white"><i class="bi bi-chat-left-text"></i> Manage Feedback</a>
            <a href="<?php echo SITE_URL; ?>/admin/settings" class="list-group-item list-group-item-action bg-dark text-white"><i class="bi bi-gear"></i> System Settings</a>
        </div>
    </div>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <div class="container-fluid">
                <button class="btn btn-primary" id="menu-toggle"><i class="bi bi-list"></i></button>

                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php echo $_SESSION['user_name']; ?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="<?php echo SITE_URL; ?>/user/profile">My Profile</a>
                                <a class="dropdown-item" href="<?php echo SITE_URL; ?>/public/pages/index">View Site</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?php echo SITE_URL; ?>/public/users/logout">Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid p-4">
        <?php flash('admin_message'); ?>