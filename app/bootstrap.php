<?php

// Registra rotas e instancia dependências
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
$basePath = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
$basePath = $basePath === '.' ? '' : $basePath;

// Helpers para gerar URLs
$routeUrl = static function (string $route = 'home', array $params = []) use ($basePath): string {
    $query = http_build_query(array_merge(['url' => $route], $params));
    return ($basePath ?: '') . '/index.php' . ($query ? '?' . $query : '');
};

$assetUrl = static function (string $path) use ($basePath): string {
    return ($basePath ?: '') . '/' . ltrim($path, '/');
};

require_once __DIR__ . '/Support/portal_helpers.php';
require_once __DIR__ . '/Models/PortalRepository.php';
require_once __DIR__ . '/Models/PostsModel.php';
require_once __DIR__ . '/Models/CategoriesModel.php';
require_once __DIR__ . '/Models/UsersModel.php';
require_once __DIR__ . '/Models/CommentsModel.php';
require_once __DIR__ . '/Models/SettingsModel.php';
require_once __DIR__ . '/Core/App.php';

$basePortalData = require __DIR__ . '/Data/portal_content.php';

// Dados são persistidos em sessão para simular um banco
$repo = new PortalRepository($basePortalData, $assetUrl);
$postsModel = new PostsModel($repo);
$categoriesModel = new CategoriesModel($repo, $assetUrl);
$usersModel = new UsersModel($repo);
$commentsModel = new CommentsModel($repo);
$settingsModel = new SettingsModel($repo);
$view = new View(__DIR__ . '/Views');
$router = new Router();

// Controllers
require_once __DIR__ . '/Controllers/PublicController.php';
require_once __DIR__ . '/Controllers/AdminController.php';
require_once __DIR__ . '/Controllers/AuthController.php';

$publicController = new PublicController($view, $repo, $routeUrl, $assetUrl);
$adminController = new AdminController($view, $repo, $routeUrl, $assetUrl, $postsModel, $categoriesModel, $usersModel, $commentsModel, $settingsModel);
$authController = new AuthController($repo, $routeUrl);

// Rotas públicas
$router->get('home', fn () => $publicController->home());
$router->get('login', fn () => $publicController->login());
$router->get('categoria', fn () => $publicController->categoria());
$router->get('publicacoes', fn () => $publicController->publicacoes());
$router->get('publicacao', fn () => $publicController->publicacao());
$router->get('conta', fn () => $publicController->conta());
$router->post('publicacao/comentar', fn () => $publicController->comentar());

// Autenticação
$router->post('processar-login-publico', fn () => $authController->loginPublico());
$router->post('processar-cadastro-publico', fn () => $authController->cadastroPublico());
$router->get('logout-publico', fn () => $authController->logoutPublico());
$router->post('processar-login', fn () => $authController->loginAdmin());
$router->get('admin/logout', fn () => $authController->logoutAdmin());

// Admin
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

$app = new App($router, $view, $repo, $routeUrl, $assetUrl);

return $app;

