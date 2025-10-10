<?php require_once APPROOT . '/views/admin/includes/header.php'; ?>

<h1 class="h3 mb-4 text-gray-800"><?php echo $data['title']; ?></h1>

<div class="row">

    <!-- Total Users Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Users</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['total_users']; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-people-fill fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Cars Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Cars</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['total_cars']; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-car-front-fill fa-2x text-gray-300"></i>
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
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['total_bookings']; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-calendar-check fa-2x text-gray-300"></i>
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
                        <div class="h5 mb-0 font-weight-bold text-gray-800">$<?php echo number_format($data['total_revenue'], 2); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-cash-stack fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Links -->
<div class="row">
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
            </div>
            <div class="card-body">
                <a href="<?php echo SITE_URL; ?>/admin/cars/add" class="btn btn-primary">Add New Car</a>
                <a href="<?php echo SITE_URL; ?>/admin/bookings" class="btn btn-info">View Pending Bookings</a>
            </div>
        </div>
    </div>
</div>


<?php require_once APPROOT . '/views/admin/includes/footer.php'; ?>