<?php
$portalData = $portalData ?? require __DIR__ . '/../../Data/portal_content.php';
$categories = $portalData['categories'];
$posts = array_values(array_filter($portalData['posts'], static function (array $post): bool {
    return ($post['status'] ?? 'Publicado') === 'Publicado';
}));
$initialQuery = trim($_GET['q'] ?? '');

include __DIR__ . '/../partials/header.php';
?>

<section class="publications-page">
    <div class="publications-hero">
        <div class="container">
            <h1>Todas as Publicações</h1>
            <p>Acesse todo o acervo de matérias do O Editorial.</p>
        </div>
    </div>

    <div class="container publications-content" data-publications-page>
        <div class="publications-filters">
            <label class="publications-search">
                <span class="publications-search-icon">⌕</span>
                <input type="search" placeholder="Buscar publicações..." value="<?= htmlspecialchars($initialQuery) ?>" data-publications-search>
            </label>

            <div class="publications-chips">
                <button type="button" class="publications-chip is-active" data-publications-filter="todos">Todas</button>
                <?php foreach ($categories as $slug => $category): ?>
                    <button type="button" class="publications-chip" data-publications-filter="<?= htmlspecialchars($slug) ?>">
                        <?= htmlspecialchars($category['name']) ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <p class="publications-count" data-publications-count><?= count($posts) ?> publicações encontradas</p>

        <div class="publications-grid" data-publications-grid>
            <?php foreach ($posts as $post): ?>
                <?php $category = $categories[$post['category']]; ?>
                <article class="publication-card" data-publication-item data-category="<?= htmlspecialchars($post['category']) ?>" data-title="<?= htmlspecialchars($post['title']) ?>" data-excerpt="<?= htmlspecialchars($post['excerpt']) ?>">
                    <a class="publication-card-media" href="<?= htmlspecialchars($routeUrl('publicacao', ['slug' => $post['slug']])) ?>">
                        <img src="<?= htmlspecialchars($category['cover']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                    </a>
                    <div class="publication-card-body">
                        <a class="post-tag post-tag-<?= htmlspecialchars($category['tag_class']) ?>" href="<?= htmlspecialchars($routeUrl('categoria', ['slug' => $post['category']])) ?>">
                            <?= htmlspecialchars($category['name']) ?>
                        </a>
                        <h2><a href="<?= htmlspecialchars($routeUrl('publicacao', ['slug' => $post['slug']])) ?>"><?= htmlspecialchars($post['title']) ?></a></h2>
                        <p><?= htmlspecialchars($post['excerpt']) ?></p>
                        <div class="publication-card-meta">
                            <span><?= htmlspecialchars($post['author_short'] ?? $post['author']) ?></span>
                            <span><?= htmlspecialchars($post['date']) ?></span>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>
