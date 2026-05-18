<?php

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\PublicController;
use App\Core\App;
use App\Core\Database;
use App\Core\Router;
use App\Core\View;
use App\Models\CategoriesModel;
use App\Models\CommentsModel;
use App\Models\PortalRepository;
use App\Models\PostsModel;
use App\Models\SettingsModel;
use App\Models\UsersModel;
use Bramus\Router\Router as HttpRouter;

$rootPath = dirname(__DIR__);

require $rootPath . '/vendor/autoload.php';

session_start();
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
$basePath   = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
$basePath   = $basePath === '.' ? '' : $basePath;

$routeUrl = static function (string $route = 'home', array $params = []) use ($basePath): string {
    $path = $route === 'home' ? '/' : '/' . $route;
    if ($params !== []) {
        $path .= '?' . http_build_query($params);
    }

    return ($basePath !== '' ? $basePath : '') . $path;
};

$assetUrl = static function (string $path) use ($basePath): string {
    $prefix = $basePath !== '' ? $basePath : '';

    return $prefix . '/' . ltrim($path, '/');
};

$basePortalData = require __DIR__ . '/Data/portal_content.php';
$pdo            = Database::connect($rootPath);

$repo            = new PortalRepository($basePortalData, $assetUrl, $pdo);
$postsModel      = new PostsModel($repo);
$categoriesModel = new CategoriesModel($repo, $assetUrl);
$usersModel      = new UsersModel($repo);
$commentsModel   = new CommentsModel($repo);
$settingsModel   = new SettingsModel($repo);
$view            = new View(__DIR__ . '/Views');
$router          = new Router();

$publicController = new PublicController($view, $repo, $routeUrl, $assetUrl);
$adminController  = new AdminController(
    $view,
    $repo,
    $routeUrl,
    $assetUrl,
    $postsModel,
    $categoriesModel,
    $usersModel,
    $commentsModel,
    $settingsModel
);
$authController = new AuthController($routeUrl);

$router->get('home', fn () => $publicController->home());
$router->get('login', fn () => $publicController->login());
$router->get('categoria', fn () => $publicController->categoria());
$router->get('publicacoes', fn () => $publicController->publicacoes());
$router->get('publicacao', fn () => $publicController->publicacao());
$router->get('conta', fn () => $publicController->conta());
$router->post('publicacao/comentar', fn () => $publicController->comentar());

$router->post('processar-login-publico', fn () => $authController->loginPublico());
$router->post('processar-cadastro-publico', fn () => $authController->cadastroPublico());
$router->get('logout-publico', fn () => $authController->logoutPublico());
$router->post('processar-login', fn () => $authController->loginAdmin());
$router->get('admin/logout', fn () => $authController->logoutAdmin());

$router->get('admin/login', fn () => $adminController->login());

$router->get('admin/posts', fn () => $adminController->postsLista());
$router->get('admin/posts/novo', fn () => $adminController->postsCadastro());
$router->get('admin/posts/editar', fn () => $adminController->postsCadastro());
$router->post('admin/posts/salvar', fn () => $adminController->postsSalvar());
$router->get('admin/posts/excluir', fn () => $adminController->postsExcluir());

$router->get('admin/usuarios', fn () => $adminController->usuariosLista());
$router->get('admin/usuarios/novo', fn () => $adminController->usuariosCadastro());
$router->get('admin/usuarios/editar', fn () => $adminController->usuariosCadastro());
$router->post('admin/usuarios/salvar', fn () => $adminController->usuariosSalvar());
$router->get('admin/usuarios/excluir', fn () => $adminController->usuariosExcluir());

$router->get('admin/categorias', fn () => $adminController->categoriasLista());
$router->get('admin/categorias/novo', fn () => $adminController->categoriasCadastro());
$router->get('admin/categorias/editar', fn () => $adminController->categoriasCadastro());
$router->post('admin/categorias/salvar', fn () => $adminController->categoriasSalvar());
$router->get('admin/categorias/excluir', fn () => $adminController->categoriasExcluir());

$router->get('admin/comentarios', fn () => $adminController->comentariosLista());
$router->get('admin/comentarios/status', fn () => $adminController->comentariosStatus());
$router->get('admin/comentarios/excluir', fn () => $adminController->comentariosExcluir());

$router->get('admin/configuracoes', fn () => $adminController->configuracoes());
$router->post('admin/configuracoes/salvar', fn () => $adminController->configuracoesSalvar());

$httpRouter = new HttpRouter();
if ($basePath !== '') {
    $httpRouter->setBasePath($basePath);
}

$registerHttpRoute = static function (string $logicalRoute, string $method) use ($httpRouter, $router): void {
    $path = $logicalRoute === 'home' ? '/' : '/' . $logicalRoute;
    $handler = static function () use ($router, $logicalRoute): void {
        $router->dispatch($logicalRoute);
    };

    if ($method === 'GET') {
        $httpRouter->get($path, $handler);
        return;
    }

    $httpRouter->post($path, $handler);
};

foreach ([
    'home', 'login', 'categoria', 'publicacoes', 'publicacao', 'conta',
    'logout-publico', 'admin/logout', 'admin/login',
    'admin/posts', 'admin/posts/novo', 'admin/posts/editar', 'admin/posts/excluir',
    'admin/usuarios', 'admin/usuarios/novo', 'admin/usuarios/editar', 'admin/usuarios/excluir',
    'admin/categorias', 'admin/categorias/novo', 'admin/categorias/editar', 'admin/categorias/excluir',
    'admin/comentarios', 'admin/comentarios/status', 'admin/comentarios/excluir',
    'admin/configuracoes',
] as $getRoute) {
    $registerHttpRoute($getRoute, 'GET');
}

foreach ([
    'publicacao/comentar', 'processar-login-publico', 'processar-cadastro-publico',
    'processar-login', 'admin/posts/salvar', 'admin/usuarios/salvar',
    'admin/categorias/salvar', 'admin/configuracoes/salvar',
] as $postRoute) {
    $registerHttpRoute($postRoute, 'POST');
}

return new App($httpRouter, $router, $view, $repo, $basePath, $routeUrl, $assetUrl);
