<div class="py-5 text-center">
  <h1 class="display-5 fw-bold">Find Your Perfect Ride</h1>
  <p class="lead text-muted">Affordable rentals across the Philippines. Book online in minutes.</p>
  <a href="/cars" class="btn btn-primary btn-lg">Browse Cars</a>
</div>

<?php if (!empty($featured)): ?>
<h2 class="h4 mt-5">Featured Cars</h2>
<div class="row g-4 mt-1">
  <?php foreach ($featured as $car): ?>
  <div class="col-12 col-sm-6 col-lg-4">
    <div class="card h-100 shadow-sm">
      <img src="<?= htmlspecialchars($car['image'] ?: '/assets/images/placeholder-car.jpg') ?>" class="card-img-top" alt="<?= htmlspecialchars($car['brand'].' '.$car['model']) ?>">
      <div class="card-body d-flex flex-column">
        <h3 class="h6 mb-1"><?= htmlspecialchars($car['brand'].' '.$car['model']) ?></h3>
        <div class="text-muted mb-2"><?= htmlspecialchars($car['type']) ?></div>
        <div class="mt-auto fw-bold text-primary">₱<?= number_format((float)$car['price_per_day'], 2) ?>/day</div>
        <a href="/car?id=<?= (int)$car['id'] ?>" class="btn btn-outline-primary w-100 mt-2">View Details</a>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>
