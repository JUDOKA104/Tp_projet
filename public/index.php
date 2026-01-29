<?php
session_start();

require_once __DIR__ . '/../src/Core/Autoloader.php';
use App\Core\Autoloader;
use App\Controller\ShopController;
use App\Controller\AuthController;
use App\Controller\AdminController;
// use App\Controller\HomeController; // On le créera après

Autoloader::register();
$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'boutique':
        $controller = new ShopController();
        $controller->index();
        break;

    case 'home':
        echo "<h1>Bienvenue sur l'accueil (En construction)</h1>";
        echo "<a href='index.php?page=boutique'>Aller à la boutique</a>";
        break;

    case 'login':
        (new AuthController())->login();
        break;

    case 'register':
        (new AuthController())->register();
        break;

    case 'logout':
        (new AuthController())->logout();
        break;

    case 'admin':
        (new AdminController())->index();
        break;

    case 'admin_add':
        (new AdminController())->add();
        break;

    case 'admin_delete':
        (new AdminController())->delete();
        break;

    default:
        echo "404 - Page introuvable";
        break;
}