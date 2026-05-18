<?php

namespace App\Core;

use App\Models\PortalRepository;
use Bramus\Router\Router as BramusRouter;

class App
{
    private BramusRouter $httpRouter;
    private Router $router;
    private View $view;
    private PortalRepository $repo;
    private string $basePath;
    /** @var callable */
    private $routeUrl;
    /** @var callable */
    private $assetUrl;

    public function __construct(
        BramusRouter $httpRouter,
        Router $router,
        View $view,
        PortalRepository $repo,
        string $basePath,
        callable $routeUrl,
        callable $assetUrl
    ) {
        $this->httpRouter = $httpRouter;
        $this->router     = $router;
        $this->view       = $view;
        $this->repo       = $repo;
        $this->basePath   = $basePath;
        $this->routeUrl   = $routeUrl;
        $this->assetUrl   = $assetUrl;
    }

    public function router(): Router
    {
        return $this->router;
    }

    public function httpRouter(): BramusRouter
    {
        return $this->httpRouter;
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
        $legacyQueryRoute = isset($_GET['url']) && is_string($_GET['url']) && $_GET['url'] !== ''
            ? trim($_GET['url'], '/')
            : null;

        $logicalRoute = RouteRequest::resolve($this->basePath);
        $_GET['url']  = $logicalRoute;

        if ($legacyQueryRoute !== null) {
            $this->router->dispatch($logicalRoute);
            return;
        }

        if ($this->router->has($logicalRoute)) {
            $this->httpRouter->run();
            return;
        }

        $this->router->dispatch($logicalRoute);
    }
}
