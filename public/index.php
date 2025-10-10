<?php
$page_title = "Welcome to CRMS";
// Include the header
require_once '../includes/header.php';
?>

<!-- Hero Section -->
<div class="bg-light p-5 rounded-lg mb-5 text-center">
    <div class="container">
        <h1 class="display-4">Find Your Perfect Rental Car</h1>
        <p class="lead">Easy, affordable, and convenient. Your next adventure starts here.</p>
        <hr class="my-4">
        <p>We offer a wide range of cars to suit your needs, from compact city cars to spacious SUVs.</p>
        <a class="btn btn-primary btn-lg" href="cars.php" role="button">
            <i class="fas fa-car"></i> Browse All Cars
        </a>
    </div>
</div>

<!-- How It Works Section -->
<div class="container mb-5">
    <h2 class="text-center mb-4">How It Works</h2>
    <div class="row text-center">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="fa-stack fa-2x mb-3">
                        <i class="fas fa-circle fa-stack-2x text-primary"></i>
                        <i class="fas fa-mouse-pointer fa-stack-1x fa-inverse"></i>
                    </div>
                    <h5 class="card-title">1. Choose a Car</h5>
                    <p class="card-text">Browse our collection and find the car that fits your needs.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                     <div class="fa-stack fa-2x mb-3">
                        <i class="fas fa-circle fa-stack-2x text-primary"></i>
                        <i class="fas fa-calendar-alt fa-stack-1x fa-inverse"></i>
                    </div>
                    <h5 class="card-title">2. Book & Pay</h5>
                    <p class="card-text">Select your dates and complete the secure payment process.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                     <div class="fa-stack fa-2x mb-3">
                        <i class="fas fa-circle fa-stack-2x text-primary"></i>
                        <i class="fas fa-key fa-stack-1x fa-inverse"></i>
                    </div>
                    <h5 class="card-title">3. Enjoy Your Ride</h5>
                    <p class="card-text">Pick up your car and enjoy the freedom of the open road.</p>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Featured Cars Section -->
<div class="container">
    <h2 class="text-center mb-4">Featured Cars</h2>
    <div class="row">
        <!-- This section will be populated dynamically from the database later. -->
        <!-- Placeholder Card 1 -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="https://placehold.co/600x400/CCCCCC/FFFFFF/png?text=Sedan" class="card-img-top" alt="Featured Car 1">
                <div class="card-body">
                    <h5 class="card-title">Comfort Sedan</h5>
                    <p class="card-text">Perfect for city trips and business travel. Great fuel efficiency.</p>
                    <p class="card-text"><strong>$50 / day</strong></p>
                    <a href="cars.php" class="btn btn-primary">View Details</a>
                </div>
            </div>
        </div>
        <!-- Placeholder Card 2 -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="https://placehold.co/600x400/999999/FFFFFF/png?text=SUV" class="card-img-top" alt="Featured Car 2">
                <div class="card-body">
                    <h5 class="card-title">Family SUV</h5>
                    <p class="card-text">Spacious and safe, ideal for family vacations and road trips.</p>
                    <p class="card-text"><strong>$80 / day</strong></p>
                     <a href="cars.php" class="btn btn-primary">View Details</a>
                </div>
            </div>
        </div>
        <!-- Placeholder Card 3 -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="https://placehold.co/600x400/333333/FFFFFF/png?text=Van" class="card-img-top" alt="Featured Car 3">
                <div class="card-body">
                    <h5 class="card-title">Luxury Van</h5>
                    <p class="card-text">Travel with a large group in comfort and style.</p>
                     <p class="card-text"><strong>$120 / day</strong></p>
                    <a href="cars.php" class="btn btn-primary">View Details</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Include the footer
require_once '../includes/footer.php';
?>