<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Security;
use App\Core\Database;

class CarsController extends Controller
{
    public function __construct()
    {
        Auth::requireAdmin();
    }

    public function index(): void
    {
        $db = Database::connection();
        $cars = $db->query('SELECT * FROM cars ORDER BY created_at DESC')->fetchAll();
        $this->view('admin/cars/index', ['cars' => $cars]);
    }

    public function createForm(): void
    {
        $this->view('admin/cars/create');
    }

    public function create(): void
    {
        $token = $_POST['csrf_token'] ?? '';
        if (!Security::verifyCsrf($token)) {
            http_response_code(400);
            echo 'Invalid CSRF token';
            return;
        }
        $brand = trim($_POST['brand'] ?? '');
        $model = trim($_POST['model'] ?? '');
        $type = $_POST['type'] ?? 'Other';
        $price = (float)($_POST['price_per_day'] ?? 0);
        $availability = $_POST['availability'] ?? 'available';
        $desc = trim($_POST['description'] ?? '');
        $image = trim($_POST['image'] ?? '');
        if (!$brand || !$model || $price <= 0) {
            $this->view('admin/cars/create', ['error' => 'Brand, model, and price are required.']);
            return;
        }
        $db = Database::connection();
        $stmt = $db->prepare('INSERT INTO cars(brand, model, type, price_per_day, image, availability, description) VALUES(?,?,?,?,?,?,?)');
        $stmt->execute([$brand, $model, $type, $price, $image, $availability, $desc]);
        header('Location: /admin/cars');
        exit;
    }
}
