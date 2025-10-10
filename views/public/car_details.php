<?php require_once APPROOT . '/views/public/includes/header.php'; ?>

<div class="container mt-5">
    <?php if (!empty($data['car'])) : ?>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <img src="<?php echo SITE_URL; ?>/assets/images/cars/<?php echo $data['car']->image; ?>" class="card-img-top" alt="<?php echo $data['car']->brand . ' ' . $data['car']->model; ?>">
                    <div class="card-body">
                        <h2 class="card-title"><?php echo $data['car']->brand . ' ' . $data['car']->model; ?></h2>
                        <p><strong>Type:</strong> <?php echo $data['car']->type; ?></p>
                        <p>This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>

                        <!-- Placeholder for more car details -->
                        <h5>Specifications</h5>
                        <ul>
                            <li>Feature 1</li>
                            <li>Feature 2</li>
                            <li>Feature 3</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center">Rental Details</h4>
                        <hr>
                        <p class="text-center" style="font-size: 24px;"><strong>$<?php echo $data['car']->price_per_day; ?></strong> / day</p>
                        <p class="text-muted text-center">Availability:
                            <?php if ($data['car']->availability) : ?>
                                <span class="badge bg-success">Available</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Not Available</span>
                            <?php endif; ?>
                        </p>
                        <div class="d-grid">
                            <a href="<?php echo SITE_URL; ?>/user/bookings/new/<?php echo $data['car']->id; ?>" class="btn btn-primary btn-lg">Book Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="alert alert-danger text-center">Car not found.</div>
    <?php endif; ?>
</div>

<?php require_once APPROOT . '/views/public/includes/footer.php'; ?>