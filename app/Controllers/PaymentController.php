<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Core\Database;

class PaymentController extends Controller
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_name(app_config('security.session_name'));
            session_start();
        }
    }

    private function requireAuth(): ?array
    {
        if (empty($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
        return $_SESSION['user'];
    }

    public function show(): void
    {
        $this->requireAuth();
        $bookingId = (int)($_GET['booking_id'] ?? 0);
        $db = Database::connection();
        $stmt = $db->prepare('SELECT * FROM bookings WHERE id=:id LIMIT 1');
        $stmt->execute(['id' => $bookingId]);
        $booking = $stmt->fetch();
        if (!$booking) { http_response_code(404); echo 'Booking not found'; return; }
        $this->view('payment/checkout', ['booking' => $booking]);
    }

    public function confirm(): void
    {
        $this->requireAuth();
        $bookingId = (int)($_POST['booking_id'] ?? 0);
        $method = $_POST['method'] ?? 'manual';
        $amount = (float)($_POST['amount'] ?? 0);
        if ($bookingId <= 0 || $amount <= 0) { $this->json(['error' => 'Invalid data'], 422); return; }
        $paymentId = Payment::createPending($bookingId, $amount, $method);
        header('Location: /invoice_generator.php?booking_id=' . $bookingId);
        exit;
    }
}
