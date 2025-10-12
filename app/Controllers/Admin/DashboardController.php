<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;

class DashboardController extends Controller
{
    public function __construct()
    {
        Auth::requireAdmin();
    }

    public function index(): void
    {
        $db = Database::connection();
        $stats = [
            'cars' => (int)$db->query('SELECT COUNT(*) FROM cars')->fetchColumn(),
            'users' => (int)$db->query("SELECT COUNT(*) FROM users WHERE role='user'")->fetchColumn(),
            'bookings' => (int)$db->query('SELECT COUNT(*) FROM bookings')->fetchColumn(),
            'revenue' => (float)$db->query("SELECT COALESCE(SUM(amount),0) FROM payments WHERE status='paid'")->fetchColumn(),
        ];
        $this->view('admin/dashboard', ['stats' => $stats]);
    }
}
