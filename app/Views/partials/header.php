<?php
    $portalData          = $portalData ?? require __DIR__ . '/../../Data/portal_content.php';
    $portalSettings      = $portalData['settings'] ?? [];
    $portalCategories    = $portalData['categories'] ?? [];
    $portalComments      = $portalData['comments'] ?? [];
    $currentRoute        = $_GET['url'] ?? 'home';
    $currentCategorySlug = $_GET['slug'] ?? '';
    $isAdminArea         = str_starts_with($currentRoute, 'admin/');
    $isAdminLogin        = $currentRoute === 'admin/login';
    $isPublicLogin       = $currentRoute === 'login';
    $searchQuery         = trim($_GET['q'] ?? '');
    $publicMode          = $_GET['modo'] ?? 'entrar';
    $publicMode          = $publicMode === 'criar' ? 'criar' : 'entrar';
    $isAdminLogged       = ! empty($_SESSION['usuario_logado']);
    $adminPortalUrl      = $isAdminLogged ? $routeUrl('admin/posts') : $routeUrl('admin/login');

    $pendingAdminComments = 0;
    foreach ($portalComments as $adminCommentItem) {
    if (($adminCommentItem['status'] ?? '') === 'Pendente') {
        $pendingAdminComments++;
    }
    }

    $pageTitle = $isAdminArea ? 'Painel | O Editorial' : 'O Editorial';

    // Título exibido no header do admin (topbar), baseado na rota atual.
    $adminHeaderTitle = 'Painel Admin';
    if ($isAdminArea) {
    if (str_starts_with($currentRoute, 'admin/posts/novo')) {
        $adminHeaderTitle = 'Nova publicação';
    } elseif (str_starts_with($currentRoute, 'admin/posts/editar')) {
        $adminHeaderTitle = 'Editar publicação';
    } elseif (str_starts_with($currentRoute, 'admin/posts')) {
        $adminHeaderTitle = 'Publicações';
    } elseif (str_starts_with($currentRoute, 'admin/usuarios/novo')) {
        $adminHeaderTitle = 'Novo usuário';
    } elseif (str_starts_with($currentRoute, 'admin/usuarios/editar')) {
        $adminHeaderTitle = 'Editar usuário';
    } elseif (str_starts_with($currentRoute, 'admin/usuarios')) {
        $adminHeaderTitle = 'Usuários';
    } elseif (str_starts_with($currentRoute, 'admin/categorias/novo')) {
        $adminHeaderTitle = 'Nova categoria';
    } elseif (str_starts_with($currentRoute, 'admin/categorias/editar')) {
        $adminHeaderTitle = 'Editar categoria';
    } elseif (str_starts_with($currentRoute, 'admin/categorias')) {
        $adminHeaderTitle = 'Categorias';
    } elseif (str_starts_with($currentRoute, 'admin/comentarios')) {
        $adminHeaderTitle = 'Comentários';
    } elseif (str_starts_with($currentRoute, 'admin/configuracoes')) {
        $adminHeaderTitle = 'Configurações';
    }
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle) ?></title>
    <link rel="icon" href="<?php echo htmlspecialchars($assetUrl('favicon.svg')) ?>" type="image/svg+xml">
    <link rel="stylesheet" href="<?php echo htmlspecialchars($assetUrl('css/style.css')) ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
<div class="page-shell">
<?php if ($isAdminArea && ! $isAdminLogin): ?>
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <div class="admin-sidebar-brand">
                <a class="admin-brand-link" href="<?php echo htmlspecialchars($routeUrl('admin/posts')) ?>">
                    <span>
                        <strong><?php echo htmlspecialchars($portalSettings['nome_site'] ?? 'O Editorial') ?></strong>
                        <small>Painel Admin</small>
                    </span>
                </a>
            </div>

            <div class="admin-sidebar-label">Menu</div>

            <nav class="admin-sidebar-nav">
                <a href="<?php echo htmlspecialchars($routeUrl('admin/posts')) ?>" class="<?php echo str_starts_with($currentRoute, 'admin/posts') ? 'is-active' : '' ?>">Publicações</a>
                <a href="<?php echo htmlspecialchars($routeUrl('admin/comentarios')) ?>" class="<?php echo str_starts_with($currentRoute, 'admin/comentarios') ? 'is-active' : '' ?>">
                    Comentários
                    <?php if ($pendingAdminComments > 0): ?>
                        <span class="admin-sidebar-badge"><?php echo $pendingAdminComments ?></span>
                    <?php endif; ?>
                </a>
                <a href="<?php echo htmlspecialchars($routeUrl('admin/categorias')) ?>" class="<?php echo str_starts_with($currentRoute, 'admin/categorias') ? 'is-active' : '' ?>">Categorias</a>
                <a href="<?php echo htmlspecialchars($routeUrl('admin/usuarios')) ?>" class="<?php echo str_starts_with($currentRoute, 'admin/usuarios') ? 'is-active' : '' ?>">Usuários</a>
                <a href="<?php echo htmlspecialchars($routeUrl('admin/configuracoes')) ?>" class="<?php echo str_starts_with($currentRoute, 'admin/configuracoes') ? 'is-active' : '' ?>">Configurações</a>
            </nav>

            <div class="admin-sidebar-divider"></div>

            <a class="admin-sidebar-linkout" href="<?php echo htmlspecialchars($routeUrl('home')) ?>">Ver site público</a>
            <a class="admin-sidebar-linkout" href="<?php echo htmlspecialchars($routeUrl('admin/logout')) ?>">Sair do painel</a>

            <div class="admin-sidebar-account">
                <div>
                    <strong><?php echo htmlspecialchars($_SESSION['usuario_nome'] ?? 'Admin Sistema') ?></strong>
                    <small><?php echo htmlspecialchars($_SESSION['usuario_email'] ?? 'admin@oeditorial.com.br') ?></small>
                </div>
            </div>
        </aside>

        <div class="admin-workspace">
            <header class="admin-topbar">
                <div class="admin-topbar-breadcrumb">
                    <span><?php echo htmlspecialchars($adminHeaderTitle) ?></span>
                </div>
                <div class="admin-topbar-actions">
                </div>
            </header>
<?php else: ?>
    <header class="public-header">
        <div class="public-topbar">
            <div class="container">
                <span data-current-date>Carregando data...</span>
                <span><?php echo htmlspecialchars($portalSettings['contact_email'] ?? 'contato@oeditorial.com.br') ?></span>
            </div>
        </div>

        <div class="public-navbar">
            <div class="container">
                <a class="brand" href="<?php echo htmlspecialchars($routeUrl('home')) ?>">
                    <span class="brand-badge">OE</span>
                    <span class="brand-name"><?php echo htmlspecialchars($portalSettings['nome_site'] ?? 'O Editorial') ?></span>
                </a>

                <nav class="nav-links">
                    <a href="<?php echo htmlspecialchars($routeUrl('home')) ?>" class="<?php echo $currentRoute === 'home' ? 'is-active' : '' ?>">Home</a>
                    <?php foreach ($portalCategories as $slug => $navCategory): ?>
                        <a href="<?php echo htmlspecialchars($routeUrl('categoria', ['slug' => $slug])) ?>" class="<?php echo $currentRoute === 'categoria' && $currentCategorySlug === $slug ? 'is-active' : '' ?>">
                            <?php echo htmlspecialchars($navCategory['name']) ?>
                        </a>
                    <?php endforeach; ?>
                </nav>

                <div class="navbar-actions">
                    <form class="navbar-search" action="<?php echo htmlspecialchars($routeUrl('publicacoes')) ?>" method="get">
                        <input type="hidden" name="url" value="publicacoes">
                        <label class="navbar-search-field">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <circle cx="11" cy="11" r="6"></circle>
                                <path d="M20 20l-3.5-3.5"></path>
                            </svg>
                            <input type="search" name="q" value="<?php echo htmlspecialchars($searchQuery) ?>" placeholder="Buscar publicações..." aria-label="Buscar publicações">
                        </label>
                    </form>
                    <a class="btn-neutral btn-admin-access" href="<?php echo htmlspecialchars($adminPortalUrl) ?>">
                        <?php echo $isAdminLogged ? 'Portal Admin' : 'Área Admin' ?>
                    </a>
                    <?php $publicAccountUrl = isset($_SESSION['usuario_publico_nome']) ? $routeUrl('conta') : $routeUrl('login', ['modo' => $publicMode]); ?>
                    <a class="<?php echo $isPublicLogin ? 'btn-primary' : 'btn-secondary' ?>" href="<?php echo htmlspecialchars($publicAccountUrl) ?>">
                        <?php echo isset($_SESSION['usuario_publico_nome']) ? 'Minha conta' : 'Entrar' ?>
                    </a>
                </div>
            </div>
        </div>
    </header>
<?php endif; ?>

    <?php if (isset($_SESSION['alerta'])): ?>
        <div class="container feedback-area">
            <div class="alert alert-<?php echo htmlspecialchars($_SESSION['alerta']['tipo']) ?>">
                <?php echo htmlspecialchars($_SESSION['alerta']['mensagem']) ?>
            </div>
            <?php unset($_SESSION['alerta']); ?>
        </div>
    <?php endif; ?>

    <div class="content-shell">
        <main class="<?php echo $isAdminArea && ! $isAdminLogin ? 'admin-main' : ($isAdminArea ? 'admin-auth-main' : 'page-main') ?>">
