<?php
require_once '../config.php';
require_once '../classes/Car.php';

$car = new Car($pdo);
$cars = $car->getAll();

include '../includes/header.php';
?>

<h2>Our Cars</h2>
<div class="row">
    <?php foreach ($cars as $car_data): ?>
        <div class="col-md-4">
            <div class="card mb-4">
                <img src="../images/<?php echo $car_data['image']; ?>" class="card-img-top" alt="<?php echo $car_data['brand'] . ' ' . $car_data['model']; ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $car_data['brand'] . ' ' . $car_data['model']; ?></h5>
                    <p class="card-text">Type: <?php echo $car_data['type']; ?></p>
                    <p class="card-text">Price: $<?php echo $car_data['price_per_day']; ?>/day</p>
                    <a href="booking.php?car_id=<?php echo $car_data['id']; ?>" class="btn btn-primary">Book Now</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include '../includes/footer.php'; ?>