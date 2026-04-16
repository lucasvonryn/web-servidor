<?php
$portalData = $portalData ?? require __DIR__ . '/../../Data/portal_content.php';
$categories = $portalData['categories'];
$posts = array_values(array_filter($portalData['posts'], static function (array $post): bool {
    return ($post['status'] ?? 'Publicado') === 'Publicado';
}));
$query = trim((string) ($_GET['q'] ?? ''));
$lower = static function (string $value): string {
    if (function_exists('mb_strtolower')) {
        return mb_strtolower($value, 'UTF-8');
    }

    return strtolower($value);
};

$queryLower = $lower($query);

if ($queryLower !== '') {
    $posts = array_values(array_filter($posts, static function (array $post) use ($queryLower): bool {
        $haystack = (string) (($post['title'] ?? '') . ' ' . ($post['excerpt'] ?? ''));
        return $haystack !== '' && (function_exists('mb_stripos')
            ? mb_stripos($haystack, $queryLower, 0, 'UTF-8') !== false
            : stripos($haystack, $queryLower) !== false);
    }));
}

include __DIR__ . '/../partials/header.php';
?>

<section class="publications-page">
    <div class="publications-hero">
        <div class="container">
            <h1>Todas as Publicações</h1>
            <p>Acesse todo o acervo de matérias do O Editorial.</p>
        </div>
    </div>

    <div class="container publications-content">
        <p class="publications-count">
            <?= count($posts) ?>
            <?= count($posts) === 1 ? 'publicação encontrada' : 'publicações encontradas' ?>
            <?php if ($queryLower !== ''): ?>
                para "<strong><?= htmlspecialchars($query) ?></strong>"
            <?php endif; ?>
        </p>

        <?php if (empty($posts)): ?>
            <div class="publication-comment-empty">
                Nenhuma publicação encontrada com esse termo.
            </div>
        <?php else: ?>
            <div class="publications-grid">
                <?php foreach ($posts as $post): ?>
                    <?php $category = $categories[$post['category']]; ?>
                    <article class="publication-card">
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
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>
