<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Car;
use App\Models\Booking;

class BookingController extends Controller
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

    public function showForm(): void
    {
        $this->requireAuth();
        $carId = (int)($_GET['car_id'] ?? 0);
        $car = Car::find($carId);
        if (!$car) { http_response_code(404); echo 'Car not found'; return; }
        $this->view('booking/form', ['car' => $car]);
    }

    public function create(): void
    {
        $user = $this->requireAuth();
        $carId = (int)($_POST['car_id'] ?? 0);
        $start = $_POST['start_date'] ?? '';
        $end = $_POST['end_date'] ?? '';
        $pickup = trim($_POST['pickup_location'] ?? '');
        $car = Car::find($carId);
        if (!$car) { $this->json(['error' => 'Car not found'], 404); return; }
        if (!$start || !$end) { $this->json(['error' => 'Dates required'], 422); return; }
        $days = (strtotime($end) - strtotime($start)) / 86400 + 1;
        if ($days <= 0 || $days > 60) { $this->json(['error' => 'Invalid date range'], 422); return; }
        $total = $days * (float)$car['price_per_day'];
        $bookingId = Booking::create((int)$user['id'], $carId, $start, $end, $pickup, $total);
        header('Location: /payment?booking_id=' . $bookingId);
        exit;
    }
}
