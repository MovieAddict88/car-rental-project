<?php
require __DIR__ . '/../app/bootstrap.php';

use App\Core\Router;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\CarsController;

$router = new Router();

$router->get('/', [DashboardController::class, 'index']);
$router->get('/cars', [CarsController::class, 'index']);
$router->get('/cars/create', [CarsController::class, 'createForm']);
$router->post('/cars/create', [CarsController::class, 'create']);

$router->dispatch('/admin');
