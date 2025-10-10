<?php require_once APPROOT . '/views/user/includes/header.php'; ?>

<div class="jumbotron jumbotron-fluid text-center">
    <div class="container">
        <h1 class="display-4">Welcome, <?php echo $data['user_name']; ?>!</h1>
        <p class="lead">This is your personal dashboard. From here, you can manage your bookings and update your profile.</p>
        <hr class="my-4">
        <p>Ready to hit the road?</p>
        <a class="btn btn-primary btn-lg" href="<?php echo SITE_URL; ?>/public/pages/index" role="button">Browse Cars</a>
        <a class="btn btn-secondary btn-lg" href="<?php echo SITE_URL; ?>/user/bookings" role="button">View My Bookings</a>
    </div>
</div>

<?php require_once APPROOT . '/views/user/includes/footer.php'; ?>