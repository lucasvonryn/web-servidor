<?php

require_once __DIR__ . '/Router.php';
require_once __DIR__ . '/View.php';

class App
{
    private Router $router;
    private View $view;
    private PortalRepository $repo;
    private $routeUrl;
    private $assetUrl;

    public function __construct(Router $router, View $view, PortalRepository $repo, callable $routeUrl, callable $assetUrl)
    {
        $this->router = $router;
        $this->view = $view;
        $this->repo = $repo;
        $this->routeUrl = $routeUrl;
        $this->assetUrl = $assetUrl;
    }

    public function router(): Router
    {
        return $this->router;
    }

    public function view(): View
    {
        return $this->view;
    }

    public function repo(): PortalRepository
    {
        return $this->repo;
    }

    public function routeUrl(): callable
    {
        return $this->routeUrl;
    }

    public function assetUrl(): callable
    {
        return $this->assetUrl;
    }

    public function run(): void
    {
        $url = $_GET['url'] ?? 'home';
        $this->router->dispatch($url);
    }
}

