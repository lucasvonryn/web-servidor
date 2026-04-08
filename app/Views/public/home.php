<?php
$portalData = require __DIR__ . '/../../Data/portal_content.php';
$categories = $portalData['categories'];
$latestPosts = $portalData['posts'];
$featuredSlides = $portalData['featured_slides'];

include __DIR__ . '/../partials/header.php';
?>

<div class="portal-home">
    <section class="hero-home">
        <div class="container">
            <div class="hero-home-panel hero-carousel" data-carousel>
                <div class="hero-carousel-viewport">
                    <div class="hero-carousel-track" data-carousel-track>
                        <?php foreach ($featuredSlides as $index => $post): ?>
                            <?php $category = $categories[$post['category']]; ?>
                            <section class="hero-carousel-slide<?= $index === 0 ? ' is-active' : '' ?>" data-carousel-slide>
                                <article class="featured-card featured-card-hero">
                                    <div class="featured-card-media">
                                        <img src="<?= htmlspecialchars($category['cover']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                                    </div>
                                    <div class="featured-card-overlay">
                                        <a class="post-tag post-tag-<?= htmlspecialchars($category['tag_class']) ?>" href="<?= htmlspecialchars($routeUrl('categoria', ['slug' => $post['category']])) ?>">
                                            <?= htmlspecialchars($category['name']) ?>
                                        </a>
                                        <h2><?= htmlspecialchars($post['title']) ?></h2>
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
                        &larr;
                    </button>
                    <div class="hero-carousel-dots">
                        <?php foreach ($featuredSlides as $index => $slide): ?>
                            <button type="button" class="hero-carousel-dot<?= $index === 0 ? ' is-active' : '' ?>" data-carousel-dot="<?= $index ?>" aria-label="Ir para o slide <?= $index + 1 ?>"></button>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="hero-carousel-arrow" data-carousel-next aria-label="Próximo slide">
                        &rarr;
                    </button>
                </div>
            </div>
        </div>
    </section>

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

    <section class="latest-section">
        <div class="container">
            <div class="section-heading">
                <h3>Últimas Publicações</h3>
                <a href="<?= htmlspecialchars($routeUrl('home')) ?>#todas">Ver todas</a>
            </div>

            <div class="post-grid" id="todas">
                <?php foreach ($latestPosts as $post): ?>
                    <?php $category = $categories[$post['category']]; ?>
                    <article class="news-card">
                        <div class="news-card-media">
                            <img src="<?= htmlspecialchars($category['cover']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                        </div>
                        <div class="news-card-body">
                            <a class="post-tag post-tag-<?= htmlspecialchars($category['tag_class']) ?>" href="<?= htmlspecialchars($routeUrl('categoria', ['slug' => $post['category']])) ?>">
                                <?= htmlspecialchars($category['name']) ?>
                            </a>
                            <h4><?= htmlspecialchars($post['title']) ?></h4>
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

    <section class="categories-section">
        <div class="container">
            <div class="section-heading">
                <h3>Explore por Categoria</h3>
            </div>

            <div class="category-grid">
                <?php foreach ($categories as $slug => $category): ?>
                    <a class="category-card accent-<?= htmlspecialchars($category['accent']) ?>" href="<?= htmlspecialchars($routeUrl('categoria', ['slug' => $slug])) ?>">
                        <span class="category-name"><?= htmlspecialchars($category['name']) ?></span>
                        <p><?= htmlspecialchars($category['description']) ?></p>
                        <div class="category-meta">
                            <span><?= htmlspecialchars($category['count_label']) ?></span>
                            <span>&rarr;</span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
