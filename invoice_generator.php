<?php
require_once 'includes/bootstrap.php';
require_once APPROOT . '/assets/vendor/fpdf/fpdf.php';

if(!isLoggedIn() || !isset($_GET['booking_id'])){
    redirect('public/pages/index');
}

$booking_id = $_GET['booking_id'];

// Fetch booking, user and car details
$bookingModel = new Booking(); // Manually instantiate model
$userModel = new User();
$carModel = new Car();

$booking = $bookingModel->getBookingById($booking_id);

// Security check: ensure the logged-in user owns this booking or is an admin
if($_SESSION['user_id'] != $booking->user_id && !isAdmin()){
    redirect('user/dashboard');
}

$user = $userModel->findUserByEmail($_SESSION['user_email']); // Or fetch by booking->user_id
$car = $carModel->getCarById($booking->car_id);

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Header
$pdf->Cell(40, 10, 'CRMS Invoice');
$pdf->Ln(20);

// Invoice Details
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(40, 10, 'Invoice #: ' . 'INV-' . str_pad($booking->id, 5, '0', STR_PAD_LEFT));
$pdf->Ln();
$pdf->Cell(40, 10, 'Booking Date: ' . date('d M Y', strtotime($booking->created_at)));
$pdf->Ln(15);


// Customer Details
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Billed To:');
$pdf->Ln();
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(40, 7, $user->name);
$pdf->Ln();
$pdf->Cell(40, 7, $user->email);
$pdf->Ln(20);


// Table Header
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(100, 10, 'Description', 1);
$pdf->Cell(30, 10, 'Start Date', 1);
$pdf->Cell(30, 10, 'End Date', 1);
$pdf->Cell(30, 10, 'Total', 1);
$pdf->Ln();

// Table Row
$pdf->SetFont('Arial', '', 12);
$rental_desc = 'Rental of ' . $car->brand . ' ' . $car->model;
$pdf->Cell(100, 10, $rental_desc, 1);
$pdf->Cell(30, 10, date('d-m-Y', strtotime($booking->start_date)), 1);
$pdf->Cell(30, 10, date('d-m-Y', strtotime($booking->end_date)), 1);
$pdf->Cell(30, 10, '$' . number_format($booking->total_price, 2), 1);
$pdf->Ln();

// Total
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(160, 10, 'Grand Total', 1);
$pdf->Cell(30, 10, '$' . number_format($booking->total_price, 2), 1);
$pdf->Ln(20);

// Footer
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 10, 'Thank you for your business!', 0, 1, 'C');


$pdf->Output('D', 'Invoice-'.$booking->id.'.pdf');
?>