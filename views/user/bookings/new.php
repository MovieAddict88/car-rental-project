<?php require_once APPROOT . '/views/user/includes/header.php'; ?>

<div class="container">
    <h2 class="text-center mb-4">Book a Car</h2>
    <div class="row">
        <!-- Car Details -->
        <div class="col-md-6">
            <div class="card">
                <img src="<?php echo SITE_URL; ?>/assets/images/cars/<?php echo $data['car']->image; ?>" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $data['car']->brand . ' ' . $data['car']->model; ?></h5>
                    <p class="card-text">Price: <strong>$<?php echo $data['car']->price_per_day; ?> / day</strong></p>
                </div>
            </div>
        </div>

        <!-- Booking Form -->
        <div class="col-md-6">
            <div class="card card-body bg-light">
                <h3>Select Rental Dates</h3>
                <form action="<?php echo SITE_URL; ?>/user/bookings/new/<?php echo $data['car']->id; ?>" method="post">
                    <div class="form-group mb-3">
                        <label for="start_date">Start Date: <sup>*</sup></label>
                        <input type="date" name="start_date" class="form-control <?php echo (!empty($data['start_date_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo date('Y-m-d'); ?>">
                        <span class="invalid-feedback"><?php echo $data['start_date_err']; ?></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="end_date">End Date: <sup>*</sup></label>
                        <input type="date" name="end_date" class="form-control <?php echo (!empty($data['end_date_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                        <span class="invalid-feedback"><?php echo $data['end_date_err']; ?></span>
                    </div>
                    <p>The total price will be calculated upon submission.</p>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">Request Booking</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<?php require_once APPROOT . '/views/user/includes/footer.php'; ?>