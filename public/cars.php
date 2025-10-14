<?php
include('../includes/header.php');

// Fetch all cars from the database
$stmt = $pdo->query("SELECT * FROM cars WHERE availability = 1");
$cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Our Fleet</h2>
<p>Browse our selection of available cars for rent.</p>

<div class="row">
    <?php if (count($cars) > 0): ?>
        <?php foreach ($cars as $car): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="<?php echo BASE_URL; ?>/assets/images/<?php echo htmlspecialchars($car['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></h5>
                        <p class="card-text">Type: <?php echo htmlspecialchars($car['type']); ?></p>
                        <p class="card-text"><strong><?php echo htmlspecialchars(format_currency((float)$car['price_per_day'])); ?> / day</strong></p>
                        <a href="car_details.php?id=<?php echo $car['id']; ?>" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col">
            <p>No cars available at the moment. Please check back later.</p>
        </div>
    <?php endif; ?>
</div>

<?php include('../includes/footer.php'); ?>