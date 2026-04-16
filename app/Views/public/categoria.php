<?php
$portalData = $portalData ?? require __DIR__ . '/../../Data/portal_content.php';
$categories = $portalData['categories'];
$allPosts = array_values(array_filter($portalData['posts'], static function (array $post): bool {
    return ($post['status'] ?? 'Publicado') === 'Publicado';
}));
$categorySlug = $_GET['slug'] ?? 'tecnologia';
$category = $categories[$categorySlug] ?? null;

if (!$category) {
    http_response_code(404);
    include __DIR__ . '/../partials/header.php';
    ?>
    <section class="category-page">
        <div class="container category-empty-state">
            <h1>Categoria não encontrada</h1>
            <p>A categoria solicitada não existe ou ainda não foi cadastrada.</p>
            <a class="btn-secondary" href="<?= htmlspecialchars($routeUrl('home')) ?>">Voltar para a home</a>
        </div>
    </section>
    <?php
    include __DIR__ . '/../partials/footer.php';
    return;
}

$categoryPosts = array_values(array_filter($allPosts, static function (array $post) use ($categorySlug): bool {
    return $post['category'] === $categorySlug;
}));
$categoryPostsCount = count($categoryPosts);

include __DIR__ . '/../partials/header.php';
?>

<section class="category-page">
    <div class="category-hero accent-<?= htmlspecialchars($category['accent']) ?>">
        <div class="container">
            <div class="category-hero-copy">
                <h1><?= htmlspecialchars($category['name']) ?></h1>
                <p><?= htmlspecialchars($category['description']) ?></p>
                <span class="category-pill"><?= htmlspecialchars($categoryPostsCount . ' ' . ($categoryPostsCount === 1 ? 'publicação' : 'publicações')) ?></span>
            </div>
        </div>
    </div>

    <div class="container category-content">
        <div class="category-post-grid">
            <?php foreach ($categoryPosts as $post): ?>
                <?php $postCategory = $categories[$post['category']] ?? $category; ?>
                <article class="category-post-card">
                    <a class="category-post-media" href="<?= htmlspecialchars($routeUrl('publicacao', ['slug' => $post['slug']])) ?>">
                        <img src="<?= htmlspecialchars($postCategory['cover']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                    </a>
                    <div class="category-post-body">
                        <a class="post-tag post-tag-<?= htmlspecialchars($postCategory['tag_class']) ?>" href="<?= htmlspecialchars($routeUrl('categoria', ['slug' => $post['category']])) ?>">
                            <?= htmlspecialchars($postCategory['name']) ?>
                        </a>
                        <h2><a href="<?= htmlspecialchars($routeUrl('publicacao', ['slug' => $post['slug']])) ?>"><?= htmlspecialchars($post['title']) ?></a></h2>
                        <p><?= htmlspecialchars($post['excerpt']) ?></p>
                        <div class="category-post-meta">
                            <span><?= htmlspecialchars($post['author']) ?></span>
                            <span><?= htmlspecialchars($post['date']) ?></span>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <div class="category-related">
            <h3>Outras Categorias</h3>
            <div class="category-related-links">
                <?php foreach ($categories as $slug => $item): ?>
                    <?php if ($slug === $categorySlug) continue; ?>
                    <?php
                    $relatedCount = 0;
                    foreach ($allPosts as $post) {
                        if ($post['category'] === $slug) {
                            $relatedCount++;
                        }
                    }
                    ?>
                    <a class="category-link-pill accent-<?= htmlspecialchars($item['accent']) ?>" href="<?= htmlspecialchars($routeUrl('categoria', ['slug' => $slug])) ?>">
                        <?= htmlspecialchars($item['name']) ?> (<?= htmlspecialchars($relatedCount) ?>)
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>
