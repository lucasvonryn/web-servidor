<?php

namespace App\Core;

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
        if (! isset($this->routes[$name])) {
            http_response_code(404);
            $view = new View(dirname(__DIR__) . '/Views');
            $view->render('errors/404.php', [
                'route' => $name,
            ]);
            return;
        }

        ($this->routes[$name])();
    }

    public function has(string $name): bool
    {
        return isset($this->routes[$name]);
    }
}
