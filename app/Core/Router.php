<?php

class Router
{
    /** @var array<string, callable> */
    private array $routes = [];

    public function get(string $name, callable $handler): void
    {
        $this->routes[$name] = $handler;
    }

    public function post(string $name, callable $handler): void
    {
        $this->routes[$name] = $handler;
    }

    public function dispatch(string $name): void
    {
        if (!isset($this->routes[$name])) {
            http_response_code(404);
            echo '<h1>404 - Página não encontrada</h1>';
            echo 'URL atual: ' . htmlspecialchars($name);
            return;
        }

        ($this->routes[$name])();
    }
}

