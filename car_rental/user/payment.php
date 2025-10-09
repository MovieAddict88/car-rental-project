<?php
require_once '../config.php';
require_once '../classes/Booking.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['booking_id'])) {
    die("Booking ID is required.");
}

$booking_obj = new Booking($pdo);
$booking = $booking_obj->findById($_GET['booking_id']);

if (!$booking) {
    die("Booking not found.");
}

include '../includes/header.php';
?>

<h2>Payment for Booking #<?php echo $booking['id']; ?></h2>
<div class="row">
    <div class="col-md-6">
        <h4>Booking Details</h4>
        <p><strong>Start Date:</strong> <?php echo $booking['start_date']; ?></p>
        <p><strong>End Date:</strong> <?php echo $booking['end_date']; ?></p>
        <p><strong>Total Price:</strong> $<?php echo $booking['total_price']; ?></p>
    </div>
    <div class="col-md-6">
        <h4>Simulated Payment</h4>
        <p>This is a simulated payment page. Click the button below to confirm your booking.</p>
        <!--
            DEVELOPER NOTE:
            To implement real Stripe payments:
            1. Uncomment the Stripe checkout form below.
            2. Replace placeholder API keys with your actual Stripe keys.
            3. Ensure you have run `composer install` to get the Stripe PHP SDK.
        -->
        <form action="charge.php" method="post">
            <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
            <input type="hidden" name="amount" value="<?php echo $booking['total_price']; ?>">
            <button type="submit" class="btn btn-success">Confirm and Pay $<?php echo $booking['total_price']; ?></button>
        </form>

        <!--
        <h4>Pay with Stripe</h4>
        <form action="charge.php" method="post">
            <script
                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                data-key="YOUR_STRIPE_PUBLISHABLE_KEY"
                data-amount="<?php echo $booking['total_price'] * 100; ?>"
                data-name="Car Rental"
                data-description="Booking Payment"
                data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                data-locale="auto"
                data-currency="usd">
            </script>
            <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
            <input type="hidden" name="amount" value="<?php echo $booking['total_price'] * 100; ?>">
        </form>
        -->
    </div>
</div>

<?php include '../includes/footer.php'; ?>