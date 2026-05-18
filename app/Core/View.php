<?php

namespace App\Core;

class View
{
    private string $viewsPath;

    public function __construct(string $viewsPath)
    {
        $this->viewsPath = rtrim($viewsPath, '/') . '/';
    }

    public function render(string $viewRelativePath, array $data = []): void
    {
        $fullPath = $this->viewsPath . ltrim($viewRelativePath, '/');
        if (! is_file($fullPath)) {
            http_response_code(500);
            echo '<h1>500 - View não encontrada</h1>';
            echo htmlspecialchars($viewRelativePath);
            exit;
        }

        extract($data, EXTR_SKIP);
        include $fullPath;
    }
}
