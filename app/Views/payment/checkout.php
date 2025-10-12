<?php $booking = $booking ?? null; ?>
<?php if (!$booking): ?>
<div class="alert alert-danger">Booking not found.</div>
<?php else: ?>
<h1 class="h4 mb-3">Payment</h1>
<div class="card shadow-sm">
  <div class="card-body">
    <p class="mb-1">Booking #<?= (int)$booking['id'] ?></p>
    <p class="mb-1">Dates: <?= htmlspecialchars($booking['start_date']) ?> to <?= htmlspecialchars($booking['end_date']) ?></p>
    <p class="mb-3">Total: <strong>₱<?= number_format((float)$booking['total_price'], 2) ?></strong></p>

    <form method="post" action="/payment/confirm" class="row g-3">
      <input type="hidden" name="booking_id" value="<?= (int)$booking['id'] ?>">
      <input type="hidden" name="amount" value="<?= (float)$booking['total_price'] ?>">
      <div class="col-12">
        <label class="form-label">Payment Method</label>
        <select name="method" class="form-select" required>
          <option value="paypal">PayPal (Sandbox)</option>
          <option value="paymaya">PayMaya</option>
          <option value="gcash">GCash</option>
          <option value="paymongo">PayMongo</option>
          <option value="bank">Bank Transaction</option>
          <option value="manual">Manual Transaction</option>
        </select>
      </div>
      <div class="col-12">
        <button class="btn btn-primary">Confirm and Generate Invoice</button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>
