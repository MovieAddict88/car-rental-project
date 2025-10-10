<?php
require_once 'config/config.php';
require_once 'libs/fpdf.php';

// --- Authentication and Authorization ---
if (!isset($_SESSION['user_id'])) {
    die("Access denied. You must be logged in.");
}

if (!isset($_GET['booking_id']) || !filter_var($_GET['booking_id'], FILTER_VALIDATE_INT)) {
    die("Invalid booking ID.");
}

$booking_id = $_GET['booking_id'];
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

// --- Data Fetching ---
try {
    $query = "
        SELECT
            b.id as booking_id, b.start_date, b.end_date, b.total_price, b.created_at as booking_date,
            u.name as user_name, u.email as user_email,
            c.brand, c.model, c.price_per_day,
            p.method as payment_method, p.transaction_id, p.payment_date,
            s_name.setting_value as system_name,
            s_email.setting_value as system_email,
            s_phone.setting_value as system_phone,
            s_logo.setting_value as system_logo
        FROM bookings b
        JOIN users u ON b.user_id = u.id
        JOIN cars c ON b.car_id = c.id
        LEFT JOIN payments p ON b.id = p.booking_id
        LEFT JOIN settings s_name ON s_name.setting_key = 'system_name'
        LEFT JOIN settings s_email ON s_email.setting_key = 'contact_email'
        LEFT JOIN settings s_phone ON s_phone.setting_key = 'contact_phone'
        LEFT JOIN settings s_logo ON s_logo.setting_key = 'system_logo'
        WHERE b.id = ?
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute([$booking_id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
        die("Could not find invoice data for the specified booking.");
    }

    // Authorization check: User must own the booking or be an admin
    if ($user_role !== 'admin' && $data['user_id'] != $user_id) {
        die("Access Denied. You do not have permission to view this invoice.");
    }

} catch (PDOException $e) {
    die("Database error while generating invoice: " . $e->getMessage());
}


// --- PDF Generation ---
class PDF extends FPDF
{
    private $system_name;
    private $system_logo;

    function setHeaderInfo($name, $logo) {
        $this->system_name = $name;
        $this->system_logo = $logo;
    }

    // Page header
    function Header()
    {
        // Logo
        if ($this->system_logo && file_exists(__DIR__ . '/assets/images/' . $this->system_logo)) {
             $this->Image(__DIR__ . '/assets/images/' . $this->system_logo, 10, 6, 30);
        }

        // Arial bold 15
        $this->SetFont('Arial','B',20);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(30,10,'INVOICE',0,0,'C');
        // Line break
        $this->Ln(20);
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Thank you for choosing ' . $this->system_name,0,0,'C');
        $this->Ln(4);
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }

    // Invoice table
    function InvoiceTable($header, $data, $booking_details) {
        // Colors, line width and bold font
        $this->SetFillColor(240, 240, 240);
        $this->SetTextColor(0);
        $this->SetDrawColor(200, 200, 200);
        $this->SetLineWidth(.3);
        $this->SetFont('','B');

        // Header
        $w = array(100, 30, 30, 30);
        for($i=0;$i<count($header);$i++)
            $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
        $this->Ln();

        // Data
        $this->SetFont('');
        $fill = false;

        // Calculate number of days
        $start = new DateTime($booking_details['start_date']);
        $end = new DateTime($booking_details['end_date']);
        $days = $end->diff($start)->format("%a");
        if($days == 0) $days = 1;

        $this->Cell($w[0], 8, $data['item'], 'LR', 0, 'L', $fill);
        $this->Cell($w[1], 8, 'P' . number_format($data['price_per_day'], 2), 'LR', 0, 'R', $fill);
        $this->Cell($w[2], 8, $days, 'LR', 0, 'C', $fill);
        $this->Cell($w[3], 8, 'P' . number_format($data['total'], 2), 'LR', 0, 'R', $fill);
        $this->Ln();

        // Closing line
        $this->Cell(array_sum($w),0,'','T');
        $this->Ln();

        // Total
        $this->SetFont('','B');
        $this->Cell($w[0] + $w[1] + $w[2], 8, 'Total Amount', 0, 0, 'R');
        $this->Cell($w[3], 8, 'P' . number_format($data['total'], 2), 1, 1, 'R', true);

    }
}

// Create instance of PDF
$pdf = new PDF();
$pdf->setHeaderInfo($data['system_name'], $data['system_logo']);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);

// --- Invoice Header ---
$pdf->SetFont('','B', 12);
$pdf->Cell(100, 6, 'Bill To:', 0, 0);
$pdf->Cell(0, 6, 'Invoice Details:', 0, 1);

$pdf->SetFont('','', 11);
$pdf->Cell(100, 6, htmlspecialchars($data['user_name']), 0, 0);
$pdf->Cell(40, 6, 'Invoice #:', 0, 0);
$pdf->Cell(0, 6, 'INV-' . str_pad($data['booking_id'], 6, '0', STR_PAD_LEFT), 0, 1);

$pdf->Cell(100, 6, htmlspecialchars($data['user_email']), 0, 0);
$pdf->Cell(40, 6, 'Booking Date:', 0, 0);
$pdf->Cell(0, 6, date('M d, Y', strtotime($data['booking_date'])), 0, 1);

$pdf->Cell(100, 6, '', 0, 0); // Spacer
$pdf->Cell(40, 6, 'Payment Date:', 0, 0);
$pdf->Cell(0, 6, date('M d, Y', strtotime($data['payment_date'])), 0, 1);

$pdf->Ln(15);

// --- Invoice Body ---
$table_header = array('Description', 'Price/Day', 'Days', 'Total');
$item_data = array(
    'item' => 'Rental of ' . htmlspecialchars($data['brand'] . ' ' . $data['model']),
    'price_per_day' => $data['price_per_day'],
    'total' => $data['total_price']
);

$pdf->InvoiceTable($table_header, $item_data, $data);

$pdf->Ln(10);

// --- Payment Details ---
$pdf->SetFont('','B', 12);
$pdf->Cell(0, 6, 'Payment Information', 0, 1);
$pdf->SetFont('','', 11);
$pdf->Cell(40, 6, 'Payment Method:', 0, 0);
$pdf->Cell(0, 6, htmlspecialchars($data['payment_method']), 0, 1);
$pdf->Cell(40, 6, 'Transaction ID:', 0, 0);
$pdf->Cell(0, 6, htmlspecialchars($data['transaction_id']), 0, 1);
$pdf->Cell(40, 6, 'Status:', 0, 0);
$pdf->Cell(0, 6, 'Paid in Full', 0, 1);


// --- Output PDF ---
$pdf->Output('D', 'Invoice-'.$data['booking_id'].'.pdf');
?>