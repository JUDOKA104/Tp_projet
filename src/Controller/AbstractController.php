<?php
namespace App\Controller;

abstract class AbstractController
{
    protected function render(string $viewPath, array $data = []): void
    {
        extract($data);

        ob_start();

        require_once __DIR__ . '/../../views/' . $viewPath . '.php';

        $content = ob_get_clean();

        require_once __DIR__ . '/../../views/layout.php';
    }

    protected function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }
}