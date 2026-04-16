<?php
    $portalData   = $portalData ?? require __DIR__ . '/../../../Data/portal_content.php';
    $categories   = $portalData['categories'];
    $accentLabels = [
    'tech'          => 'Indigo',
    'politica'      => 'Vermelho',
    'ciencia'       => 'Esmeralda',
    'meio-ambiente' => 'Verde',
    'cultura'       => 'Roxo',
    ];
    include __DIR__ . '/../../partials/header.php';
?>

<section class="admin-page-shell" data-admin-categories-page data-admin-categories-page-size="4">
    <header class="admin-page-header">
        <a href="<?php echo htmlspecialchars($routeUrl('admin/categorias/novo')) ?>" class="admin-primary-button">+ Nova categoria</a>
    </header>

    <div class="admin-toolbar">
        <label class="admin-search">
            <span><i class="bi bi-search"></i></span>
            <input type="search" placeholder="Buscar categoria..." data-admin-categories-search>
        </label>
    </div>

    <p class="admin-results-copy" data-admin-categories-count><?php echo count($categories) ?> categorias encontradas</p>

    <section class="admin-table-card">
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
                    <tr data-admin-category-row data-name="<?php echo htmlspecialchars(strtolower($adminCategory['name'])) ?>" data-slug="<?php echo htmlspecialchars(strtolower($adminCategory['slug'])) ?>" data-description="<?php echo htmlspecialchars(strtolower($adminCategory['description'])) ?>">
                        <td><span class="post-tag post-tag-<?php echo htmlspecialchars($adminCategory['tag_class']) ?>"><?php echo htmlspecialchars($adminCategory['name']) ?></span></td>
                        <td><code><?php echo htmlspecialchars($adminCategory['slug']) ?></code></td>
                        <td><?php echo htmlspecialchars($adminCategory['description']) ?></td>
                        <td><?php echo htmlspecialchars((string) ($adminCategory['count'] ?? 0)) ?></td>
                        <td class="admin-table-actions">
                            <a href="<?php echo htmlspecialchars($routeUrl('admin/categorias/editar', ['slug' => $adminCategory['slug']])) ?>" class="admin-icon-action" aria-label="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="<?php echo htmlspecialchars($routeUrl('admin/categorias/excluir', ['slug' => $adminCategory['slug']])) ?>" class="admin-icon-action admin-icon-action-danger" aria-label="Excluir" onclick="return confirm('Deseja excluir esta categoria?')">
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
