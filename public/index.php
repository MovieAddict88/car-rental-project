<?php
require __DIR__ . '/../app/bootstrap.php';

use App\Core\Router;
use App\Controllers\PublicController;
use App\Controllers\AuthController;
use App\Controllers\BookingController;
use App\Controllers\PaymentController;

$router = new Router();

// Public routes
$router->get('/', [PublicController::class, 'home']);
$router->get('/cars', [PublicController::class, 'cars']);
$router->get('/car', [PublicController::class, 'carDetails']);

// Auth routes
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);

// Booking routes (protected)
$router->get('/booking', [BookingController::class, 'showForm']);
$router->post('/booking/create', [BookingController::class, 'create']);

// Payment routes
$router->get('/payment', [PaymentController::class, 'show']);
$router->post('/payment/confirm', [PaymentController::class, 'confirm']);

$router->dispatch();
