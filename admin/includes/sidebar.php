<nav id="sidebar">
    <div class="sidebar-header">
        <h3><i class="fas fa-car-shield"></i> CRMS Admin</h3>
    </div>

    <ul class="list-unstyled components">
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>
        <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
            <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        </li>
        <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage_cars.php' ? 'active' : ''; ?>">
            <a href="manage_cars.php"><i class="fas fa-car"></i> Manage Cars</a>
        </li>
        <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage_bookings.php' ? 'active' : ''; ?>">
            <a href="manage_bookings.php"><i class="fas fa-calendar-check"></i> Manage Bookings</a>
        </li>
        <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage_users.php' ? 'active' : ''; ?>">
            <a href="manage_users.php"><i class="fas fa-users"></i> Manage Users</a>
        </li>
        <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage_payments.php' ? 'active' : ''; ?>">
            <a href="manage_payments.php"><i class="fas fa-credit-card"></i> Manage Payments</a>
        </li>
        <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?>">
            <a href="reports.php"><i class="fas fa-chart-line"></i> Reports</a>
        </li>
        <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'feedback.php' ? 'active' : ''; ?>">
            <a href="feedback.php"><i class="fas fa-comment-dots"></i> Manage Feedback</a>
        </li>
        <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
            <a href="settings.php"><i class="fas fa-cog"></i> System Settings</a>
        </li>
    </ul>

    <ul class="list-unstyled CTAs">
        <li>
            <a href="<?php echo SITE_URL; ?>/public/index.php" class="article">Back to Public Site</a>
        </li>
         <li>
            <a href="<?php echo SITE_URL; ?>/user/logout.php" class="download">Logout</a>
        </li>
    </ul>
</nav>