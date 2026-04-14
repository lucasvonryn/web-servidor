<?php
$portalData = $portalData ?? require __DIR__ . '/../../Data/portal_content.php';
$portalSettings = $portalData['settings'] ?? [];
$portalCategories = $portalData['categories'] ?? [];
$portalComments = $portalData['comments'] ?? [];
$currentRoute = $_GET['url'] ?? 'home';
$currentCategorySlug = $_GET['slug'] ?? '';
$isAdminArea = str_starts_with($currentRoute, 'admin/');
$isAdminLogin = $currentRoute === 'admin/login';
$isPublicLogin = $currentRoute === 'login';
$searchQuery = trim($_GET['q'] ?? '');
$publicMode = $_GET['modo'] ?? 'entrar';
$publicMode = $publicMode === 'criar' ? 'criar' : 'entrar';
$isAdminLogged = !empty($_SESSION['usuario_logado']);
$adminPortalUrl = $isAdminLogged ? $routeUrl('admin/painel') : $routeUrl('admin/login');
$adminInitials = strtoupper(substr(trim((string) ($_SESSION['usuario_nome'] ?? 'AS')), 0, 1) . substr(trim((string) preg_replace('/^\S+\s+/', '', $_SESSION['usuario_nome'] ?? '')), 0, 1));
$pendingAdminComments = 0;
foreach ($portalComments as $adminCommentItem) {
    if (($adminCommentItem['status'] ?? '') === 'Pendente') {
        $pendingAdminComments++;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal <?= htmlspecialchars($portalSettings['nome_site'] ?? 'O Editorial') ?></title>
    <link rel="stylesheet" href="<?= htmlspecialchars($assetUrl('css/style.css')) ?>">
</head>
<body>
<div class="page-shell">
<?php if ($isAdminArea && !$isAdminLogin): ?>
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <div class="admin-sidebar-brand">
                <a class="admin-brand-link" href="<?= htmlspecialchars($routeUrl('admin/painel')) ?>">
                    <span class="admin-brand-badge">OE</span>
                    <span>
                        <strong><?= htmlspecialchars($portalSettings['nome_site'] ?? 'O Editorial') ?></strong>
                        <small>Painel Admin</small>
                    </span>
                </a>
            </div>

            <div class="admin-sidebar-label">Menu</div>

            <nav class="admin-sidebar-nav">
                <a href="<?= htmlspecialchars($routeUrl('admin/painel')) ?>" class="<?= $currentRoute === 'admin/painel' ? 'is-active' : '' ?>">Painel</a>
                <a href="<?= htmlspecialchars($routeUrl('admin/usuarios')) ?>" class="<?= str_starts_with($currentRoute, 'admin/usuarios') ? 'is-active' : '' ?>">Usuários</a>
                <a href="<?= htmlspecialchars($routeUrl('admin/categorias')) ?>" class="<?= str_starts_with($currentRoute, 'admin/categorias') ? 'is-active' : '' ?>">Categorias</a>
                <a href="<?= htmlspecialchars($routeUrl('admin/posts')) ?>" class="<?= str_starts_with($currentRoute, 'admin/posts') ? 'is-active' : '' ?>">Publicações</a>
                <a href="<?= htmlspecialchars($routeUrl('admin/comentarios')) ?>" class="<?= str_starts_with($currentRoute, 'admin/comentarios') ? 'is-active' : '' ?>">
                    Comentários
                    <?php if ($pendingAdminComments > 0): ?>
                        <span class="admin-sidebar-badge"><?= $pendingAdminComments ?></span>
                    <?php endif; ?>
                </a>
                <a href="<?= htmlspecialchars($routeUrl('admin/configuracoes')) ?>" class="<?= str_starts_with($currentRoute, 'admin/configuracoes') ? 'is-active' : '' ?>">Configurações</a>
            </nav>

            <div class="admin-sidebar-divider"></div>

            <a class="admin-sidebar-linkout" href="<?= htmlspecialchars($routeUrl('home')) ?>">Ver site público</a>

            <div class="admin-sidebar-account">
                <span class="admin-avatar"><?= htmlspecialchars($adminInitials ?: 'AS') ?></span>
                <div>
                    <strong><?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'Admin Sistema') ?></strong>
                    <small><?= htmlspecialchars($_SESSION['usuario_email'] ?? 'admin@oeditorial.com.br') ?></small>
                </div>
            </div>
        </aside>

        <div class="admin-workspace">
            <header class="admin-topbar">
                <div class="admin-topbar-breadcrumb">
                    <span>Painel</span>
                </div>
                <div class="admin-topbar-actions">
                    <button type="button" class="admin-icon-button" aria-label="Notificações">
                        <span class="admin-notification-dot"></span>
                        &#128276;
                    </button>
                    <span class="admin-avatar admin-avatar-top"><?= htmlspecialchars($adminInitials ?: 'AS') ?></span>
                </div>
            </header>
<?php else: ?>
    <header class="public-header">
        <div class="public-topbar">
            <div class="container">
                <span data-current-date>Carregando data...</span>
                <span><?= htmlspecialchars($portalSettings['contact_email'] ?? 'contato@oeditorial.com.br') ?></span>
            </div>
        </div>

        <div class="public-navbar">
            <div class="container">
                <a class="brand" href="<?= htmlspecialchars($routeUrl('home')) ?>">
                    <span class="brand-badge">OE</span>
                    <span class="brand-name"><?= htmlspecialchars($portalSettings['nome_site'] ?? 'O Editorial') ?></span>
                </a>

                <nav class="nav-links">
                    <a href="<?= htmlspecialchars($routeUrl('home')) ?>" class="<?= $currentRoute === 'home' ? 'is-active' : '' ?>">Home</a>
                    <?php foreach ($portalCategories as $slug => $navCategory): ?>
                        <a href="<?= htmlspecialchars($routeUrl('categoria', ['slug' => $slug])) ?>" class="<?= $currentRoute === 'categoria' && $currentCategorySlug === $slug ? 'is-active' : '' ?>">
                            <?= htmlspecialchars($navCategory['name']) ?>
                        </a>
                    <?php endforeach; ?>
                </nav>

                <div class="navbar-actions">
                    <form class="navbar-search" action="<?= htmlspecialchars($routeUrl('publicacoes')) ?>" method="get">
                        <input type="hidden" name="url" value="publicacoes">
                        <label class="navbar-search-field">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <circle cx="11" cy="11" r="6"></circle>
                                <path d="M20 20l-3.5-3.5"></path>
                            </svg>
                            <input type="search" name="q" value="<?= htmlspecialchars($searchQuery) ?>" placeholder="Buscar publicações..." aria-label="Buscar publicações">
                        </label>
                    </form>
                    <a class="btn-neutral btn-admin-access" href="<?= htmlspecialchars($adminPortalUrl) ?>">
                        <?= $isAdminLogged ? 'Portal Admin' : 'Área Admin' ?>
                    </a>
                    <?php $publicAccountUrl = isset($_SESSION['usuario_publico_nome']) ? $routeUrl('conta') : $routeUrl('login', ['modo' => $publicMode]); ?>
                    <a class="<?= $isPublicLogin ? 'btn-primary' : 'btn-secondary' ?>" href="<?= htmlspecialchars($publicAccountUrl) ?>">
                        <?= isset($_SESSION['usuario_publico_nome']) ? 'Minha conta' : 'Entrar' ?>
                    </a>
                </div>
            </div>
        </div>
    </header>
<?php endif; ?>

    <?php if (isset($_SESSION['alerta'])): ?>
        <div class="container feedback-area">
            <div class="alert alert-<?= htmlspecialchars($_SESSION['alerta']['tipo']) ?>">
                <?= htmlspecialchars($_SESSION['alerta']['mensagem']) ?>
            </div>
            <?php unset($_SESSION['alerta']); ?>
        </div>
    <?php endif; ?>

    <div class="content-shell">
        <main class="<?= $isAdminArea && !$isAdminLogin ? 'admin-main' : ($isAdminArea ? 'admin-auth-main' : 'page-main') ?>">
