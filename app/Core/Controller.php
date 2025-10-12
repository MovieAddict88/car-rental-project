<?php
namespace App\Core;

class Controller
{
    protected function view(string $template, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        $viewPath = __DIR__ . '/../Views/' . $template . '.php';
        if (!file_exists($viewPath)) {
            http_response_code(404);
            echo 'View not found';
            return;
        }
        include __DIR__ . '/../../includes/header.php';
        include __DIR__ . '/../../includes/navbar.php';
        include $viewPath;
        include __DIR__ . '/../../includes/footer.php';
    }

    protected function json($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
