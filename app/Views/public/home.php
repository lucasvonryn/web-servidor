<?php
$portalData = $portalData ?? require __DIR__ . '/../../Data/portal_content.php';
$settings = $portalData['settings'];
$categories = $portalData['categories'];
$publishedPosts = array_values(array_filter($portalData['posts'], static function (array $post): bool {
    return ($post['status'] ?? 'Publicado') === 'Publicado';
}));
$latestPosts = $publishedPosts;
$latestPosts = array_slice($latestPosts, 0, (int) ($settings['itens_home'] ?? 6));
$featuredSlides = array_values(array_filter($portalData['featured_slides'], static function (array $post): bool {
    return ($post['status'] ?? 'Publicado') === 'Publicado';
}));

include __DIR__ . '/../partials/header.php';
?>

<div class="portal-home">
    <?php if (!empty($settings['show_featured'])): ?>
        <section class="hero-home">
            <div class="container">
                <div class="hero-home-panel hero-carousel" data-carousel>
                    <div class="hero-carousel-viewport">
                        <div class="hero-carousel-track" data-carousel-track>
                            <?php foreach ($featuredSlides as $index => $post): ?>
                                <?php $category = $categories[$post['category']]; ?>
                                <section class="hero-carousel-slide<?= $index === 0 ? ' is-active' : '' ?>" data-carousel-slide>
                                    <article class="featured-card featured-card-hero">
                                        <a class="featured-card-media" href="<?= htmlspecialchars($routeUrl('publicacao', ['slug' => $post['slug']])) ?>">
                                            <img src="<?= htmlspecialchars($category['cover']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                                        </a>
                                        <div class="featured-card-overlay">
                                            <a class="post-tag post-tag-<?= htmlspecialchars($category['tag_class']) ?>" href="<?= htmlspecialchars($routeUrl('categoria', ['slug' => $post['category']])) ?>">
                                                <?= htmlspecialchars($category['name']) ?>
                                            </a>
                                            <h2><a href="<?= htmlspecialchars($routeUrl('publicacao', ['slug' => $post['slug']])) ?>"><?= htmlspecialchars($post['title']) ?></a></h2>
                                            <p><?= htmlspecialchars($post['excerpt']) ?></p>
                                            <div class="post-meta">
                                                <span><?= htmlspecialchars($post['author']) ?></span>
                                                <span><?= htmlspecialchars($post['date']) ?></span>
                                            </div>
                                        </div>
                                    </article>
                                </section>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="hero-carousel-controls">
                        <button type="button" class="hero-carousel-arrow" data-carousel-prev aria-label="Slide anterior">
                            <i class="bi bi-chevron-left" aria-hidden="true"></i>
                        </button>
                        <div class="hero-carousel-dots">
                            <?php foreach ($featuredSlides as $index => $slide): ?>
                                <button type="button" class="hero-carousel-dot<?= $index === 0 ? ' is-active' : '' ?>" data-carousel-dot="<?= $index ?>" aria-label="Ir para o slide <?= $index + 1 ?>"></button>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" class="hero-carousel-arrow" data-carousel-next aria-label="Próximo slide">
                            <i class="bi bi-chevron-right" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <section class="headline-strip">
        <div class="container">
            <span class="headline-label">Destaques</span>
            <div class="headline-track">
                <span>60 novos: reforma tributária com ampla margem</span>
                <span>Cientistas descobrem molécula capaz de reverter envelhecimento celular</span>
                <span>Arquitetura urbana reinventa espaços públicos nas metrópoles</span>
            </div>
        </div>
    </section>

    <?php if (!empty($settings['show_latest'])): ?>
        <section class="latest-section">
            <div class="container">
                <div class="section-heading">
                    <h3>Últimas Publicações</h3>
                    <a href="<?= htmlspecialchars($routeUrl('publicacoes')) ?>">Ver todas</a>
                </div>

                <div class="post-grid" id="todas">
                    <?php foreach ($latestPosts as $post): ?>
                        <?php $category = $categories[$post['category']]; ?>
                        <article class="news-card">
                            <a class="news-card-media" href="<?= htmlspecialchars($routeUrl('publicacao', ['slug' => $post['slug']])) ?>">
                                <img src="<?= htmlspecialchars($category['cover']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                            </a>
                            <div class="news-card-body">
                                <a class="post-tag post-tag-<?= htmlspecialchars($category['tag_class']) ?>" href="<?= htmlspecialchars($routeUrl('categoria', ['slug' => $post['category']])) ?>">
                                    <?= htmlspecialchars($category['name']) ?>
                                </a>
                                <h4><a href="<?= htmlspecialchars($routeUrl('publicacao', ['slug' => $post['slug']])) ?>"><?= htmlspecialchars($post['title']) ?></a></h4>
                                <p><?= htmlspecialchars($post['excerpt']) ?></p>
                                <div class="post-meta">
                                    <span><?= htmlspecialchars($post['author']) ?></span>
                                    <span><?= htmlspecialchars($post['date']) ?></span>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
