<?php include __DIR__ . '/../../../includes/header.php'; ?>
<?php include __DIR__ . '/../../../includes/admin_navbar.php'; ?>
<h1 class="h4 mb-4">Dashboard</h1>
<div class="row g-3">
  <div class="col-6 col-lg-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="text-muted">Total Cars</div>
        <div class="h3 mb-0"><?= (int)$stats['cars'] ?></div>
      </div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="text-muted">Total Users</div>
        <div class="h3 mb-0"><?= (int)$stats['users'] ?></div>
      </div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="text-muted">Total Bookings</div>
        <div class="h3 mb-0"><?= (int)$stats['bookings'] ?></div>
      </div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="text-muted">Revenue</div>
        <div class="h3 mb-0">₱<?= number_format((float)$stats['revenue'], 2) ?></div>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../../../includes/footer.php'; ?>
