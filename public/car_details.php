<?php
require_once '../includes/header.php';

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // A more user-friendly approach than just dying
    echo '<div class="alert alert-danger">No car selected. Redirecting to car list...</div>';
    header("refresh:3;url=cars.php");
    exit;
}

$car_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

if ($car_id === false) {
    echo '<div class="alert alert-danger">Invalid car ID. Redirecting to car list...</div>';
    header("refresh:3;url=cars.php");
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ? AND availability = 1");
    $stmt->execute([$car_id]);
    $car = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$car) {
        echo '<div class="alert alert-warning">Car not found or is currently unavailable.</div>';
    }
} catch (PDOException $e) {
    $car = null;
    echo '<div class="alert alert-danger">Error fetching car details. Please try again later.</div>';
}
?>

<?php
if ($car) {
    // Fetch all images for this car
    $stmt_images = $pdo->prepare("SELECT * FROM car_images WHERE car_id = ? ORDER BY is_primary DESC");
    $stmt_images->execute([$car_id]);
    $images = $stmt_images->fetchAll(PDO::FETCH_ASSOC);
}
?>

<?php if ($car): ?>
<div class="row">
    <div class="col-md-8">
        <!-- Image Gallery Carousel -->
        <div id="carGallery" class="carousel slide mb-4" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php if ($images): ?>
                    <?php foreach ($images as $index => $img): ?>
                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                            <img src="<?php echo URL_ROOT . '/assets/images/' . $img['image_path']; ?>" class="d-block w-100" alt="Car gallery image">
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="carousel-item active">
                        <img src="<?php echo URL_ROOT; ?>/assets/images/placeholder.png" class="d-block w-100" alt="No image available">
                    </div>
                <?php endif; ?>
            </div>
            <?php if (count($images) > 1): ?>
            <button class="carousel-control-prev" type="button" data-bs-target="#carGallery" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carGallery" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
            <?php endif; ?>
        </div>

        <h2><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></h2>
        <p class="lead">Type: <?php echo htmlspecialchars($car['type']); ?></p>

        <h4>Description</h4>
        <p>
            This <?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?> is a fantastic choice for your travel needs. It offers a smooth ride, excellent fuel efficiency, and a comfortable interior. Perfect for city driving or long road trips.
            (This is a sample description, as one was not included in the database schema).
        </p>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Booking</h4>
                <hr>
                <p class="card-text fs-4">Price per day:</p>
                <p class="card-text display-6"><strong>₱<?php echo htmlspecialchars(number_format($car['price_per_day'], 2)); ?></strong></p>

                <?php
                // Determine the booking URL based on login status
                $booking_url = '';
                if (isset($_SESSION['user_id'])) {
                    // Assuming a booking page in the user directory
                    $booking_url = URL_ROOT . '/user/book_car.php?car_id=' . $car['id'];
                } else {
                    // Redirect to login but pass the intended destination
                    $booking_url = URL_ROOT . '/user/login.php?redirect=' . urlencode(URL_ROOT . '/user/book_car.php?car_id=' . $car['id']);
                }
                ?>
                <a href="<?php echo $booking_url; ?>" class="btn btn-success btn-lg w-100">Book Now</a>
                <p class="text-muted text-center mt-2">
                    <?php if (!isset($_SESSION['user_id'])) echo 'You need to login to book a car.'; ?>
                </p>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
    <div class="text-center">
        <a href="cars.php" class="btn btn-primary">Back to Car List</a>
    </div>
<?php endif; ?>

<?php require_once '../includes/footer.php'; ?>