<?php $car = $car ?? null; ?>
<?php if (!$car): ?>
  <div class="alert alert-danger">Car not found.</div>
<?php else: ?>
<div class="row g-4">
  <div class="col-12 col-lg-6">
    <div id="carouselCar" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="<?= htmlspecialchars($car['image'] ?: '/assets/images/placeholder-car.jpg') ?>" class="d-block w-100 rounded" alt="">
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-lg-6">
    <h1 class="h3 mb-1"><?= htmlspecialchars($car['brand'].' '.$car['model']) ?></h1>
    <div class="text-muted mb-3"><?= htmlspecialchars($car['type']) ?> · <?= htmlspecialchars($car['transmission']) ?> · <?= (int)($car['seating'] ?? 4) ?> seats</div>
    <div class="h4 text-primary">₱<?= number_format((float)$car['price_per_day'], 2) ?>/day</div>
    <p class="mt-3"><?= nl2br(htmlspecialchars($car['description'] ?? '')) ?></p>
    <div class="d-flex gap-2 mt-3">
      <a href="/login" class="btn btn-primary">Book Now</a>
      <a href="/cars" class="btn btn-outline-secondary">Back to Cars</a>
    </div>
  </div>
</div>
<?php endif; ?>
