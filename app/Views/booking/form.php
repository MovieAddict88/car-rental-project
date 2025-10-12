<h1 class="h4 mb-3">Book <?= htmlspecialchars($car['brand'].' '.$car['model']) ?></h1>
<form method="post" action="/booking/create" class="row g-3">
  <input type="hidden" name="car_id" value="<?= (int)$car['id'] ?>">
  <div class="col-12 col-md-4">
    <label class="form-label">Start Date</label>
    <input type="date" name="start_date" class="form-control" required>
  </div>
  <div class="col-12 col-md-4">
    <label class="form-label">End Date</label>
    <input type="date" name="end_date" class="form-control" required>
  </div>
  <div class="col-12 col-md-4">
    <label class="form-label">Pickup Location</label>
    <input type="text" name="pickup_location" class="form-control" required placeholder="e.g., NAIA Terminal 3">
  </div>
  <div class="col-12">
    <button class="btn btn-primary">Proceed to Payment</button>
  </div>
</form>
