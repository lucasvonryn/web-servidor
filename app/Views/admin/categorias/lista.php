<?php
$portalData = $portalData ?? require __DIR__ . '/../../../Data/portal_content.php';
$categories = $portalData['categories'];
$accentLabels = [
    'tech' => 'Indigo',
    'politica' => 'Vermelho',
    'ciencia' => 'Esmeralda',
    'meio-ambiente' => 'Verde',
    'cultura' => 'Roxo',
];
include __DIR__ . '/../../partials/header.php';
?>

<section class="admin-page-shell" data-admin-categories-page data-admin-categories-page-size="4">
    <header class="admin-page-header">
        <a href="<?= htmlspecialchars($routeUrl('admin/categorias/novo')) ?>" class="admin-primary-button">+ Nova categoria</a>
    </header>

    <div class="admin-toolbar">
        <label class="admin-search">
            <span><i class="bi bi-search"></i></span>
            <input type="search" placeholder="Buscar categoria..." data-admin-categories-search>
        </label>
    </div>

    <div class="admin-category-grid">
        <?php foreach ($categories as $adminCategory): ?>
            <article class="admin-category-card" data-admin-category-card data-name="<?= htmlspecialchars(strtolower($adminCategory['name'])) ?>" data-slug="<?= htmlspecialchars(strtolower($adminCategory['slug'])) ?>" data-description="<?= htmlspecialchars(strtolower($adminCategory['description'])) ?>">
                <div class="admin-category-card-head">
                    <div class="admin-category-label-wrap">
                        <span class="admin-category-dot accent-<?= htmlspecialchars($adminCategory['accent']) ?>"></span>
                        <span class="post-tag post-tag-<?= htmlspecialchars($adminCategory['tag_class']) ?>"><?= htmlspecialchars($adminCategory['name']) ?></span>
                    </div>
                    <div class="admin-card-actions">
                        <a href="<?= htmlspecialchars($routeUrl('admin/categorias/editar', ['slug' => $adminCategory['slug']])) ?>" class="admin-icon-action" aria-label="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="<?= htmlspecialchars($routeUrl('admin/categorias/excluir', ['slug' => $adminCategory['slug']])) ?>" class="admin-icon-action admin-icon-action-danger" aria-label="Excluir" onclick="return confirm('Deseja excluir esta categoria?')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </div>
                </div>
                <p><?= htmlspecialchars($adminCategory['description']) ?></p>
                <div class="admin-category-meta">
                    <code><?= htmlspecialchars($adminCategory['slug']) ?></code>
                    <span><?= htmlspecialchars($adminCategory['count'] ?? 0) ?> posts</span>
                </div>
                <small>Cor: <?= htmlspecialchars($accentLabels[$adminCategory['accent']] ?? $adminCategory['accent']) ?></small>
            </article>
        <?php endforeach; ?>
    </div>

    <p class="admin-results-copy" data-admin-categories-count><?= count($categories) ?> categorias encontradas</p>

    <section class="admin-table-card">
        <div class="admin-section-title">Listagem completa</div>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Slug</th>
                    <th>Descrição</th>
                    <th>Posts</th>
                    <th class="admin-table-actions">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $adminCategory): ?>
                    <tr data-admin-category-row data-name="<?= htmlspecialchars(strtolower($adminCategory['name'])) ?>" data-slug="<?= htmlspecialchars(strtolower($adminCategory['slug'])) ?>" data-description="<?= htmlspecialchars(strtolower($adminCategory['description'])) ?>">
                        <td><span class="post-tag post-tag-<?= htmlspecialchars($adminCategory['tag_class']) ?>"><?= htmlspecialchars($adminCategory['name']) ?></span></td>
                        <td><code><?= htmlspecialchars($adminCategory['slug']) ?></code></td>
                        <td><?= htmlspecialchars($adminCategory['description']) ?></td>
                        <td><?= htmlspecialchars((string) ($adminCategory['count'] ?? 0)) ?></td>
                        <td class="admin-table-actions">
                            <a href="<?= htmlspecialchars($routeUrl('admin/categorias/editar', ['slug' => $adminCategory['slug']])) ?>" class="admin-icon-action" aria-label="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="<?= htmlspecialchars($routeUrl('admin/categorias/excluir', ['slug' => $adminCategory['slug']])) ?>" class="admin-icon-action admin-icon-action-danger" aria-label="Excluir" onclick="return confirm('Deseja excluir esta categoria?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <div class="admin-pagination" data-admin-categories-pagination></div>
</section>

<?php include __DIR__ . '/../../partials/footer.php'; ?>
