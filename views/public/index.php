<?php require_once APPROOT . '/views/public/includes/header.php'; ?>

<div class="jumbotron jumbotron-fluid text-center bg-light p-5">
    <div class="container">
        <h1 class="display-4"><?php echo $data['title']; ?></h1>
        <p class="lead"><?php echo $data['description']; ?></p>
    </div>
</div>

<div class="container mt-5">
    <h2 class="text-center mb-4">Our Fleet of Cars</h2>
    <div class="row">
        <?php if (!empty($data['cars'])) : ?>
            <?php foreach($data['cars'] as $car) : ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <img src="<?php echo SITE_URL; ?>/assets/images/cars/<?php echo $car->image; ?>" class="card-img-top" alt="<?php echo $car->brand . ' ' . $car->model; ?>" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $car->brand . ' ' . $car->model; ?></h5>
                            <p class="card-text">Type: <?php echo $car->type; ?></p>
                            <p class="card-text"><strong>$<?php echo $car->price_per_day; ?> / day</strong></p>
                        </div>
                        <div class="card-footer">
                            <a href="<?php echo SITE_URL; ?>/public/pages/car_details/<?php echo $car->id; ?>" class="btn btn-primary btn-block">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p class="text-center">No cars available at the moment.</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once APPROOT . '/views/public/includes/footer.php'; ?>