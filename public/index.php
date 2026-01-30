<?php
session_start();

require_once __DIR__ . '/../src/Core/Autoloader.php';
require_once __DIR__ . '/../src/Core/Functions.php';

use App\Core\Autoloader;
use App\Core\Router;
use App\Controller\ShopController;
use App\Controller\AuthController;
use App\Controller\AdminController;

Autoloader::register();

$router = new Router();

$router->add('home', function() {
    header('Location: index.php?page=boutique');
    exit;
});

$router->add('boutique', [ShopController::class, 'index']);
$router->add('buy',      [ShopController::class, 'buy']);
$router->add('login',    [AuthController::class, 'login']);
$router->add('register', [AuthController::class, 'register']);
$router->add('profile', [AuthController::class, 'profile']);
$router->add('logout',   [AuthController::class, 'logout']);

$router->add('admin',        [AdminController::class, 'index']);
$router->add('admin_add',    [AdminController::class, 'add']);
$router->add('admin_edit',   [AdminController::class, 'edit']);
$router->add('admin_delete', [AdminController::class, 'delete']);

$router->run();