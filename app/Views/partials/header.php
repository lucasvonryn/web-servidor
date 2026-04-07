<?php
$currentRoute = $_GET['url'] ?? 'home';
$isAdminArea = str_starts_with($currentRoute, 'admin/');
$isPublicLogin = $currentRoute === 'login';
$publicMode = $_GET['modo'] ?? 'entrar';
$publicMode = $publicMode === 'criar' ? 'criar' : 'entrar';
$todayLabel = 'terça-feira, 24 de março de 2026';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal O Editorial</title>
    <link rel="stylesheet" href="<?= htmlspecialchars($assetUrl('css/style.css')) ?>">
</head>
<body>
<div class="page-shell">
<?php if ($isAdminArea): ?>
    <header class="admin-header">
        <div class="container">
            <a class="brand" href="<?= htmlspecialchars($routeUrl('admin/posts')) ?>">
                <span class="brand-badge">OE</span>
                <span class="brand-name">O Editorial Admin</span>
            </a>

            <nav class="admin-nav">
                <a href="<?= htmlspecialchars($routeUrl('admin/posts')) ?>" class="<?= $currentRoute === 'admin/posts' ? 'is-active' : '' ?>">Publicações</a>
                <a href="<?= htmlspecialchars($routeUrl('admin/posts/novo')) ?>" class="<?= $currentRoute === 'admin/posts/novo' ? 'is-active' : '' ?>">Nova postagem</a>
                <a href="<?= htmlspecialchars($routeUrl('admin/configuracoes')) ?>" class="<?= $currentRoute === 'admin/configuracoes' ? 'is-active' : '' ?>">Configurações</a>
                <a href="<?= htmlspecialchars($routeUrl('home')) ?>">Voltar ao site</a>
            </nav>
        </div>
    </header>
<?php else: ?>
    <header class="public-header">
        <div class="public-topbar">
            <div class="container">
                <span><?= $todayLabel ?></span>
                <span>contato@oeditorial.com.br</span>
            </div>
        </div>

        <div class="public-navbar">
            <div class="container">
                <a class="brand" href="<?= htmlspecialchars($routeUrl('home')) ?>">
                    <span class="brand-badge">OE</span>
                    <span class="brand-name">O Editorial</span>
                </a>

                <nav class="nav-links">
                    <a href="<?= htmlspecialchars($routeUrl('home')) ?>" class="<?= $currentRoute === 'home' ? 'is-active' : '' ?>">Home</a>
                    <a href="<?= htmlspecialchars($routeUrl('home')) ?>#tecnologia">Tecnologia</a>
                    <a href="<?= htmlspecialchars($routeUrl('home')) ?>#politica">Política</a>
                    <a href="<?= htmlspecialchars($routeUrl('home')) ?>#ciencia">Ciência</a>
                    <a href="<?= htmlspecialchars($routeUrl('home')) ?>#meio-ambiente">Meio Ambiente</a>
                    <a href="<?= htmlspecialchars($routeUrl('home')) ?>#cultura">Cultura</a>
                </nav>

                <div class="navbar-actions">
                    <a class="search-icon" href="<?= htmlspecialchars($routeUrl('home')) ?>#busca" aria-label="Buscar">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <circle cx="11" cy="11" r="6"></circle>
                            <path d="M20 20l-3.5-3.5"></path>
                        </svg>
                    </a>
                    <a class="<?= $isPublicLogin ? 'btn-primary' : 'btn-secondary' ?>" href="<?= htmlspecialchars($routeUrl('login', ['modo' => $publicMode])) ?>">
                        <?= isset($_SESSION['usuario_publico_nome']) ? 'Minha conta' : 'Entrar' ?>
                    </a>
                </div>
            </div>
        </div>
    </header>
<?php endif; ?>

    <div class="container feedback-area">
        <?php if (isset($_SESSION['alerta'])): ?>
            <div class="alert alert-<?= htmlspecialchars($_SESSION['alerta']['tipo']) ?>">
                <?= htmlspecialchars($_SESSION['alerta']['mensagem']) ?>
            </div>
            <?php unset($_SESSION['alerta']); ?>
        <?php endif; ?>
    </div>

    <div class="content-shell">
        <main class="<?= $isAdminArea ? 'container admin-page' : 'page-main' ?>">
