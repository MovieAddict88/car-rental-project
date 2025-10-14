<?php require_once('../config/config.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?php echo BASE_URL; ?>/public/index.php">CRMS</a>
        <button class="navbar-toggler border-0 p-0" type="button" id="hamburgerBtn" aria-label="Open navigation" aria-expanded="false">
            <span class="hamburger"><span></span><span></span><span></span></span>
        </button>
        <div class="collapse navbar-collapse d-none d-lg-block" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>/public/index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>/public/cars.php">Cars</a>
                </li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/user/booking_history.php">My Bookings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/user/profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/user/logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/user/login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/user/register.php">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Off-canvas navigation -->
<div id="offcanvasOverlay" class="offcanvas-nav-overlay"></div>
<nav id="offcanvasNav" class="offcanvas-nav" aria-hidden="true">
    <a href="<?php echo BASE_URL; ?>/public/index.php">Home</a>
    <a href="<?php echo BASE_URL; ?>/public/cars.php">Cars</a>
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="<?php echo BASE_URL; ?>/user/booking_history.php">My Bookings</a>
        <a href="<?php echo BASE_URL; ?>/user/profile.php">Profile</a>
        <a href="<?php echo BASE_URL; ?>/user/logout.php">Logout</a>
    <?php else: ?>
        <a href="<?php echo BASE_URL; ?>/user/login.php">Login</a>
        <a href="<?php echo BASE_URL; ?>/user/register.php">Register</a>
    <?php endif; ?>
</nav>

<div class="container mt-4">