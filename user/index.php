<?php
require __DIR__ . '/../app/bootstrap.php';

use App\Core\Router;
use App\Controllers\User\DashboardController;

$router = new Router();
$router->get('/', [DashboardController::class, 'index']);
$router->dispatch('/user');
