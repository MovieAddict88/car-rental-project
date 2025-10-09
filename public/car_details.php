<?php
include('../includes/header.php');

// Check if car ID is provided
if (!isset($_GET['id'])) {
    header("Location: cars.php");
    exit();
}

$car_id = $_GET['id'];

// Fetch car details from the database
$stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ?");
$stmt->execute([$car_id]);
$car = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$car) {
    echo "<p>Car not found.</p>";
    include('../includes/footer.php');
    exit();
}
?>

<div class="row">
    <div class="col-md-6">
        <img src="<?php echo BASE_URL; ?>/assets/images/<?php echo htmlspecialchars($car['image']); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?>">
    </div>
    <div class="col-md-6">
        <h2><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></h2>
        <p><strong>Type:</strong> <?php echo htmlspecialchars($car['type']); ?></p>
        <p><strong>Price:</strong> $<?php echo htmlspecialchars($car['price_per_day']); ?> / day</p>
        <p><strong>Description:</strong> A reliable and comfortable car perfect for city driving or long trips. Features include air conditioning, a modern sound system, and excellent fuel efficiency.</p>

        <a href="<?php echo BASE_URL; ?>/user/book_car.php?car_id=<?php echo $car['id']; ?>" class="btn btn-success btn-lg">Book Now</a>
    </div>
</div>

<?php include('../includes/footer.php'); ?>