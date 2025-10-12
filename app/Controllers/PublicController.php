<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Car;

class PublicController extends Controller
{
    public function home(): void
    {
        $featured = Car::getFeatured(6);
        $this->view('public/home', [
            'featured' => $featured,
        ]);
    }

    public function cars(): void
    {
        $filters = [
            'brand' => $_GET['brand'] ?? null,
            'type' => $_GET['type'] ?? null,
            'min_price' => $_GET['min_price'] ?? null,
            'max_price' => $_GET['max_price'] ?? null,
            'availability' => $_GET['availability'] ?? 'available',
        ];
        $cars = Car::filter($filters);
        $this->view('public/cars', [
            'cars' => $cars,
            'filters' => $filters,
        ]);
    }

    public function carDetails(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(404);
            echo 'Car not found';
            return;
        }
        $car = Car::find($id);
        if (!$car) {
            http_response_code(404);
            echo 'Car not found';
            return;
        }
        $this->view('public/car_details', [
            'car' => $car,
        ]);
    }
}
