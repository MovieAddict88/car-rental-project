<?php
require_once('config/config.php');
require_once('includes/fpdf/fpdf.php');

// Security check: ensure user is logged in and booking_id is provided
if (!isset($_SESSION['user_id']) || !isset($_GET['booking_id'])) {
    die("Access denied.");
}

$booking_id = $_GET['booking_id'];
$user_id = $_SESSION['user_id'];

// Fetch booking, user, and car details from the database
$stmt = $pdo->prepare(
    "SELECT b.*, u.name as user_name, u.email as user_email, c.brand, c.model, c.price_per_day
     FROM bookings b
     JOIN users u ON b.user_id = u.id
     JOIN cars c ON b.car_id = c.id
     WHERE b.id = ? AND b.user_id = ? AND b.status IN ('Confirmed', 'Completed')"
);
$stmt->execute([$booking_id, $user_id]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    die("Invoice not found or you do not have permission to view it.");
}

// --- PDF Generation ---

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'Car Rental Invoice', 0, 1, 'C');
        $this->Ln(10);
    }

    // Page footer
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    // Invoice details
    function InvoiceDetails($booking)
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(40, 10, 'Invoice #:');
        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 10, $booking['id'], 0, 1);

        $this->SetFont('Arial', 'B', 12);
        $this->Cell(40, 10, 'Booking Date:');
        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 10, $booking['created_at'], 0, 1);
        $this->Ln(10);

        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Bill To:', 0, 1);
        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 7, $booking['user_name'], 0, 1);
        $this->Cell(0, 7, $booking['user_email'], 0, 1);
        $this->Ln(10);
    }

    // Invoice table
    function InvoiceTable($booking)
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(100, 10, 'Description', 1);
        $this->Cell(30, 10, 'Period', 1, 0, 'C');
        $this->Cell(30, 10, 'Rate', 1, 0, 'C');
        $this->Cell(30, 10, 'Total', 1, 1, 'C');

        $this->SetFont('Arial', '', 12);
        $car_details = $booking['brand'] . ' ' . $booking['model'];
        $rental_period = (new DateTime($booking['start_date']))->format('M d') . ' - ' . (new DateTime($booking['end_date']))->format('M d, Y');
        $date1 = new DateTime($booking['start_date']);
        $date2 = new DateTime($booking['end_date']);
        $num_days = $date1->diff($date2)->days;


        $this->Cell(100, 10, $car_details, 1);
        $this->Cell(30, 10, $num_days . ' days', 1, 0, 'C');
        $this->Cell(30, 10, CURRENCY_SYMBOL . number_format((float)$booking['price_per_day'], 2), 1, 0, 'R');
        $this->Cell(30, 10, CURRENCY_SYMBOL . number_format((float)$booking['total_price'], 2), 1, 1, 'R');

        // Total
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(160, 10, 'Grand Total', 1, 0, 'R');
        $this->Cell(30, 10, CURRENCY_SYMBOL . number_format((float)$booking['total_price'], 2), 1, 1, 'R');
    }
}

// Create PDF object
$pdf = new PDF();
$pdf->AddPage();
$pdf->InvoiceDetails($booking);
$pdf->InvoiceTable($booking);
$pdf->Output('D', 'Invoice-' . $booking['id'] . '.pdf'); // D: force download
?>