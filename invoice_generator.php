<?php
require __DIR__ . '/app/bootstrap.php';

use Dompdf\Dompdf;
use App\Core\Database;

$bookingId = (int)($_GET['booking_id'] ?? 0);
if ($bookingId <= 0) { http_response_code(400); exit('Invalid booking'); }

$db = Database::connection();
$stmt = $db->prepare('SELECT b.*, u.name, u.email, c.brand, c.model FROM bookings b JOIN users u ON u.id=b.user_id JOIN cars c ON c.id=b.car_id WHERE b.id=:id LIMIT 1');
$stmt->execute(['id' => $bookingId]);
$booking = $stmt->fetch();
if (!$booking) { http_response_code(404); exit('Not found'); }

$html = '<h1>Invoice #' . $booking['id'] . '</h1>';
$html .= '<p>Customer: ' . htmlspecialchars($booking['name']) . ' (' . htmlspecialchars($booking['email']) . ')</p>';
$html .= '<p>Car: ' . htmlspecialchars($booking['brand'] . ' ' . $booking['model']) . '</p>';
$html .= '<p>Dates: ' . htmlspecialchars($booking['start_date']) . ' to ' . htmlspecialchars($booking['end_date']) . '</p>';
$html .= '<p>Total: ₱' . number_format((float)$booking['total_price'], 2) . '</p>';

try {
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream('invoice-'.$bookingId.'.pdf');
} catch (Throwable $e) {
    header('Content-Type: text/html; charset=utf-8');
    echo $html; // Fallback to HTML if PDF not available
}
