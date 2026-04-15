<?php
$portalData = $portalData ?? require __DIR__ . '/../../../Data/portal_content.php';
$categories = $portalData['categories'];
$posts = $portalData['posts'];

$publishedCount = 0;
$draftCount = 0;
foreach ($posts as $postItem) {
    if (($postItem['status'] ?? '') === 'Publicado') {
        $publishedCount++;
    } else {
        $draftCount++;
    }
}

include __DIR__ . '/../../partials/header.php';
?>

<section class="admin-page-shell" data-admin-posts-page data-admin-posts-page-size="5">
    <header class="admin-page-header">
        <a href="<?= htmlspecialchars($routeUrl('admin/posts/novo')) ?>" class="admin-primary-button">+ Nova publicação</a>
    </header>

    <div class="admin-toolbar admin-toolbar-wide">
        <label class="admin-search">
            <span><i class="bi bi-search"></i></span>
            <input type="search" placeholder="Buscar publicação ou autor..." data-admin-posts-search>
        </label>
        <div class="admin-filter-chips">
            <button type="button" class="admin-filter-chip is-active" data-admin-posts-filter="todos">Todos</button>
            <button type="button" class="admin-filter-chip" data-admin-posts-filter="publicado">Publicado</button>
            <button type="button" class="admin-filter-chip" data-admin-posts-filter="rascunho">Rascunho</button>
        </div>
    </div>

    <div class="admin-stats-grid admin-stats-grid-3">
        <article class="admin-stat-card">
            <strong><?= count($posts) ?></strong>
            <span>Total</span>
        </article>
        <article class="admin-stat-card admin-stat-card-success">
            <strong><?= $publishedCount ?></strong>
            <span>Publicados</span>
        </article>
        <article class="admin-stat-card admin-stat-card-warning">
            <strong><?= $draftCount ?></strong>
            <span>Rascunhos</span>
        </article>
    </div>

    <p class="admin-results-copy" data-admin-posts-count><?= count($posts) ?> registros encontrados</p>

    <section class="admin-table-card">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Publicação</th>
                    <th>Categoria</th>
                    <th>Autor</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th class="admin-table-actions">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($posts as $postItem): ?>
                    <?php
                    $postCategory = $categories[$postItem['category']] ?? null;
                    $postCover = $postItem['cover'] ?? ($postCategory['cover'] ?? '');
                    $statusClass = ($postItem['status'] ?? '') === 'Publicado' ? 'admin-badge-success' : 'admin-badge-warning';
                    ?>
                    <tr data-admin-post-row data-title="<?= htmlspecialchars(strtolower($postItem['title'])) ?>" data-slug="<?= htmlspecialchars(strtolower($postItem['slug'])) ?>" data-author="<?= htmlspecialchars(strtolower($postItem['author'])) ?>" data-category="<?= htmlspecialchars(strtolower($postCategory['name'] ?? '')) ?>" data-status="<?= htmlspecialchars(strtolower($postItem['status'])) ?>">
                        <td>
                            <div class="admin-post-cell">
                                <img src="<?= htmlspecialchars($postCover) ?>" alt="<?= htmlspecialchars($postItem['title']) ?>">
                                <div>
                                    <strong><?= htmlspecialchars($postItem['title']) ?></strong>
                                    <span><?= htmlspecialchars($postItem['slug']) ?></span>
                                    <?php if (!empty($postItem['featured'])): ?>
                                        <em class="admin-inline-label">destaque</em>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($postCategory['name'] ?? 'Sem categoria') ?></td>
                        <td><?= htmlspecialchars($postItem['author_short'] ?? $postItem['author']) ?></td>
                        <td><?= htmlspecialchars($postItem['date']) ?></td>
                        <td><span class="admin-badge <?= $statusClass ?>"><?= htmlspecialchars($postItem['status']) ?></span></td>
                        <td class="admin-table-actions">
                            <a href="<?= htmlspecialchars($routeUrl('publicacao', ['slug' => $postItem['slug']])) ?>" class="admin-icon-action" aria-label="Visualizar">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="<?= htmlspecialchars($routeUrl('admin/posts/editar', ['id' => $postItem['id']])) ?>" class="admin-icon-action" aria-label="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="<?= htmlspecialchars($routeUrl('admin/posts/excluir', ['id' => $postItem['id']])) ?>" class="admin-icon-action admin-icon-action-danger" aria-label="Excluir" onclick="return confirm('Deseja excluir esta publicação?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <div class="admin-pagination" data-admin-posts-pagination></div>
</section>

<?php include __DIR__ . '/../../partials/footer.php'; ?>
