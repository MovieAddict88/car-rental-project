<?php require_once APPROOT . '/views/user/includes/header.php'; ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card card-body bg-light mt-5">
            <h2 class="text-center">Confirm Payment</h2>
            <p class="text-center">Please review your booking details and proceed to payment.</p>

            <hr>

            <h4>Booking Summary</h4>
            <p><strong>Booking ID:</strong> #<?php echo $data['booking']->id; ?></p>
            <p><strong>Rental Period:</strong> <?php echo date('d M Y', strtotime($data['booking']->start_date)); ?> to <?php echo date('d M Y', strtotime($data['booking']->end_date)); ?></p>

            <div class="alert alert-info mt-3">
                <h4 class="alert-heading text-center">Total Amount Due</h4>
                <p class="display-4 text-center mb-0">$<?php echo number_format($data['booking']->total_price, 2); ?></p>
            </div>

            <div class="d-grid mt-4">
                <a href="<?php echo SITE_URL; ?>/payment_gateway.php?booking_id=<?php echo $data['booking']->id; ?>&amount=<?php echo $data['booking']->total_price; ?>" class="btn btn-success btn-lg">
                    Confirm & Pay with Sandbox
                </a>
                <a href="<?php echo SITE_URL; ?>/user/bookings" class="btn btn-secondary btn-sm mt-2">Cancel</a>
            </div>
            <p class="text-muted text-center mt-3">You will be redirected to our secure payment partner to complete your transaction. This is a sandbox environment for demonstration.</p>
        </div>
    </div>
</div>

<?php require_once APPROOT . '/views/user/includes/footer.php'; ?>