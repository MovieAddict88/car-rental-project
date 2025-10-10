<?php
require_once 'includes/bootstrap.php';

// Check if user is logged in and booking_id is set
if(!isLoggedIn() || !isset($_GET['booking_id'])){
    redirect('public/pages/index');
}

$booking_id = $_GET['booking_id'];
$amount = $_GET['amount'];

// Instantiate models
$bookingModel = new Booking();
$paymentModel = new Payment();

// Get booking details
$booking = $bookingModel->getBookingById($booking_id);

// Security check: ensure the logged-in user owns this booking
if(!$booking || $booking->user_id != $_SESSION['user_id']){
    redirect('user/dashboard');
}

// Prepare payment data
$payment_data = [
    'booking_id' => $booking_id,
    'amount' => $amount,
    'method' => 'Sandbox Gateway',
    'transaction_id' => 'txn_' . uniqid(),
    'status' => 'Completed'
];

// Add payment to the database
if($paymentModel->addPayment($payment_data)){
    // Optionally, you could update the booking status here if you had a 'Paid' status.
    // For now, we just log the payment. The booking remains 'Confirmed'.

    flash('booking_message', 'Your payment was successful! Thank you for your booking.');
    redirect('user/bookings');
} else {
    flash('booking_message', 'There was an error processing your payment. Please try again.', 'alert alert-danger');
    redirect('user/payments/pay/' . $booking_id);
}
?>