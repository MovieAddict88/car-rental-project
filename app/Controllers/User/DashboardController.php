<?php
namespace App\Controllers\User;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Booking;

class DashboardController extends Controller
{
    public function __construct()
    {
        Auth::requireUser();
    }

    public function index(): void
    {
        $user = Auth::user();
        $bookings = Booking::forUser((int)$user['id']);
        $this->view('user/dashboard', ['bookings' => $bookings]);
    }
}
