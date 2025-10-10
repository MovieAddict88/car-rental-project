<?php
$page_title = "Car Details";
require_once '../includes/header.php';

// --- Fetch car details from the database ---
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Car ID is required.");
}
$car_id = (int)$_GET['id'];

// Fetch main car details
$stmt = $conn->prepare("SELECT * FROM cars WHERE id = ? AND availability = 'available'");
$stmt->bind_param("i", $car_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Car not found or is unavailable.");
}
$car = $result->fetch_assoc();
$stmt->close();

// Fetch car gallery images
$stmt_img = $conn->prepare("SELECT image_path FROM car_images WHERE car_id = ?");
$stmt_img->bind_param("i", $car_id);
$stmt_img->execute();
$images_result = $stmt_img->get_result();
$gallery_images = $images_result->fetch_all(MYSQLI_ASSOC);
$stmt_img->close();

// Add the main image to the front of the gallery array
array_unshift($gallery_images, ['image_path' => $car['main_image']]);

// For demonstration, features can be stored in a JSON column or another table
$features = ['Air Conditioning', 'Bluetooth', 'GPS Navigation', 'Sunroof', 'Backup Camera'];


?>

<div class="container my-5">
    <div class="row">
        <!-- Image Carousel -->
        <div class="col-lg-7">
            <div id="carImageCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <?php foreach ($gallery_images as $index => $image): ?>
                        <button type="button" data-bs-target="#carImageCarousel" data-bs-slide-to="<?php echo $index; ?>" class="<?php echo $index === 0 ? 'active' : ''; ?>" aria-current="true" aria-label="Slide <?php echo $index + 1; ?>"></button>
                    <?php endforeach; ?>
                </div>
                <div class="carousel-inner rounded">
                    <?php foreach ($gallery_images as $index => $image): ?>
                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                            <img src="<?php echo SITE_URL . '/assets/images/' . htmlspecialchars($image['image_path']); ?>" class="d-block w-100" alt="<?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?>" style="height: 500px; object-fit: cover;">
                        </div>
                    <?php endforeach; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carImageCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carImageCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>

        <!-- Car Details and Booking -->
        <div class="col-lg-5">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h1 class="card-title"><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></h1>
                    <h5 class="text-muted"><?php echo htmlspecialchars($car['type']); ?></h5>
                    <p class="card-text mt-3"><?php echo htmlspecialchars($car['description']); ?></p>

                    <ul class="list-group list-group-flush my-4">
                        <li class="list-group-item d-flex justify-content-between"><strong>Year:</strong> <span><?php echo $car['year']; ?></span></li>
                        <li class="list-group-item d-flex justify-content-between"><strong>Seats:</strong> <span><?php echo $car['seats']; ?></span></li>
                        <li class="list-group-item d-flex justify-content-between"><strong>Transmission:</strong> <span><?php echo htmlspecialchars($car['transmission']); ?></span></li>
                        <li class="list-group-item d-flex justify-content-between"><strong>Fuel:</strong> <span><?php echo htmlspecialchars($car['fuel']); ?></span></li>
                    </ul>

                    <div class="my-4">
                        <h5>Key Features:</h5>
                        <div>
                            <?php foreach ($features as $feature): ?>
                                <span class="badge bg-light text-dark me-1 mb-1 p-2"><i class="fas fa-check-circle text-success me-1"></i><?php echo htmlspecialchars($feature); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="text-center bg-light p-3 rounded">
                        <p class="fs-3 mb-0"><strong>$<?php echo number_format($car['price_per_day'], 2); ?></strong> / day</p>
                    </div>

                    <a href="../user/booking.php?car_id=<?php echo $car['id']; ?>" class="btn btn-primary btn-lg w-100 mt-4">
                        <i class="fas fa-calendar-check"></i> Book Now
                    </a>
                    <p class="text-center text-muted small mt-2">You will be asked to log in or register to complete your booking.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once '../includes/footer.php';
?>