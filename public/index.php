<?php require_once '../includes/header.php'; ?>

<div class="p-5 mb-4 bg-light rounded-3">
    <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold">Welcome to <?php echo SITE_NAME; ?></h1>
        <p class="col-md-8 fs-4">Your one-stop solution for renting the best cars at the best prices. Browse our collection and book your ride today!</p>
        <a class="btn btn-primary btn-lg" href="cars.php" role="button">View Available Cars</a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <h2>Featured Cars</h2>
        <hr>
    </div>
    <?php
    try {
        // Fetch 3 random cars as featured, along with their primary image
        $query = "
            SELECT c.*, ci.image_path
            FROM cars c
            LEFT JOIN (
                SELECT car_id, image_path
                FROM car_images
                WHERE is_primary = 1
            ) ci ON c.id = ci.car_id
            WHERE c.availability = 1
            ORDER BY RAND()
            LIMIT 3
        ";
        $stmt = $pdo->query($query);
        $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($cars) {
            foreach ($cars as $car) {
                $image_url = !empty($car['image_path']) ? URL_ROOT . '/assets/images/' . $car['image_path'] : URL_ROOT . '/assets/images/placeholder.png';
                echo '<div class="col-md-4 mb-4">';
                echo '  <div class="card h-100">';
                echo '    <img src="' . $image_url . '" class="card-img-top" alt="' . htmlspecialchars($car['brand'] . ' ' . $car['model']) . '">';
                echo '    <div class="card-body">';
                echo '      <h5 class="card-title">' . htmlspecialchars($car['brand'] . ' ' . $car['model']) . '</h5>';
                echo '      <p class="card-text">Type: ' . htmlspecialchars($car['type']) . '</p>';
                echo '      <p class="card-text"><strong>₱' . htmlspecialchars(number_format($car['price_per_day'], 2)) . '/day</strong></p>';
                echo '    </div>';
                echo '    <div class="card-footer">';
                echo '      <a href="car_details.php?id=' . $car['id'] . '" class="btn btn-primary w-100">View Details</a>';
                echo '    </div>';
                echo '  </div>';
                echo '</div>';
            }
        } else {
            echo '<p>No featured cars available at the moment.</p>';
        }
    } catch (PDOException $e) {
        // Don't show detailed errors on public pages
        echo '<div class="alert alert-danger">We are having trouble loading cars right now. Please try again later.</div>';
    }
    ?>
</div>

<?php require_once '../includes/footer.php'; ?>