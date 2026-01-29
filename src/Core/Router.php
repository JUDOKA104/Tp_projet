<?php
namespace App\Core;

class Router
{
    private array $routes = [];

    /**
     * Enregistre une route
     * @param string $path Le nom de la page (ex: 'boutique')
     * @param callable|array $action L'action à exécuter (ex: [ShopController::class, 'index'])
     */
    public function add(string $path, $action): void
    {
        $this->routes[$path] = $action;
    }

    /**
     * Lance le routing
     */
    public function run(): void
    {
        $page = $_GET['page'] ?? 'home';

        if (array_key_exists($page, $this->routes)) {
            $action = $this->routes[$page];

            if (is_array($action) && count($action) === 2 && is_string($action[0])) {
                $controllerName = $action[0];
                $method = $action[1];

                $controller = new $controllerName();
                $controller->$method();
            } elseif (is_callable($action)) {
                call_user_func($action);
            }
        } else {
            echo "<h1>404 - Page introuvable</h1>";
            echo "<a href='index.php?page=home'>Retour à l'accueil</a>";
        }
    }
}