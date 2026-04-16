<?php
$portalData = $portalData ?? require __DIR__ . '/../../Data/portal_content.php';
$settings = $portalData['settings'];
$categories = $portalData['categories'];
$allPosts = array_values(array_filter($portalData['posts'], static function (array $post): bool {
    return ($post['status'] ?? 'Publicado') === 'Publicado';
}));
$allComments = $portalData['comments'];
$postSlug = trim($_GET['slug'] ?? '');
$post = null;

foreach ($allPosts as $item) {
    if (($item['slug'] ?? '') === $postSlug) {
        $post = $item;
        break;
    }
}

if (!$post) {
    http_response_code(404);
    include __DIR__ . '/../partials/header.php';
    ?>
    <section class="publication-detail-page">
        <div class="container publication-empty-state">
            <h1>Publicação não encontrada</h1>
            <p>A matéria solicitada não existe ou ainda não está disponível.</p>
            <a class="btn-secondary" href="<?= htmlspecialchars($routeUrl('publicacoes')) ?>">Voltar para publicações</a>
        </div>
    </section>
    <?php
    include __DIR__ . '/../partials/footer.php';
    return;
}

$category = $categories[$post['category']] ?? null;
$postComments = array_values(array_filter($allComments, static function (array $comment) use ($post): bool {
    return (int) ($comment['post_id'] ?? 0) === (int) $post['id'];
}));
$commentsCount = count($postComments);
$relatedPosts = array_values(array_filter($allPosts, static function (array $item) use ($post): bool {
    return $item['slug'] !== $post['slug'] && $item['category'] === $post['category'];
}));

if (count($relatedPosts) < 3) {
    foreach ($allPosts as $item) {
        if ($item['slug'] === $post['slug']) {
            continue;
        }
        if (count($relatedPosts) >= 3) {
            break;
        }
        $alreadyIncluded = false;
        foreach ($relatedPosts as $relatedPost) {
            if ($relatedPost['slug'] === $item['slug']) {
                $alreadyIncluded = true;
                break;
            }
        }
        if (!$alreadyIncluded) {
            $relatedPosts[] = $item;
        }
    }
}

$readingTime = max(1, (int) ceil(str_word_count(strip_tags($post['content'] ?? '')) / 180));
$contentParagraphs = preg_split('/\R{2,}|\n/', trim((string) ($post['content'] ?? ''))) ?: [];
$contentParagraphs = array_values(array_filter(array_map('trim', $contentParagraphs)));
if (empty($contentParagraphs)) {
    $contentParagraphs = [trim((string) ($post['excerpt'] ?? ''))];
}

$isPublicUserLogged = !empty($_SESSION['usuario_publico_logado']) && !empty($_SESSION['usuario_publico_nome']);
$loggedReaderName = trim((string) ($_SESSION['usuario_publico_nome'] ?? ''));
$commentsEnabled = !empty($settings['exibir_comentarios']);

include __DIR__ . '/../partials/header.php';
?>

<section class="publication-detail-page">
    <div class="container publication-detail-container">
        <nav class="publication-breadcrumb" aria-label="Breadcrumb">
            <a href="<?= htmlspecialchars($routeUrl('home')) ?>">Home</a>
            <span>&rsaquo;</span>
            <a href="<?= htmlspecialchars($routeUrl('categoria', ['slug' => $post['category']])) ?>"><?= htmlspecialchars($category['name'] ?? 'Categoria') ?></a>
            <span>&rsaquo;</span>
            <strong><?= htmlspecialchars($post['title']) ?></strong>
        </nav>

        <article class="publication-article">
            <header class="publication-header">
                <?php if ($category): ?>
                    <a class="post-tag post-tag-<?= htmlspecialchars($category['tag_class']) ?>" href="<?= htmlspecialchars($routeUrl('categoria', ['slug' => $post['category']])) ?>">
                        <?= htmlspecialchars($category['name']) ?>
                    </a>
                <?php endif; ?>

                <h1><?= htmlspecialchars($post['title']) ?></h1>
                <p class="publication-lead"><?= htmlspecialchars($post['excerpt']) ?></p>

                <div class="publication-top-meta">
                    <div class="publication-author-inline">
                        <strong><?= htmlspecialchars($post['author']) ?></strong>
                    </div>

                    <div class="publication-stats">
                        <span><?= htmlspecialchars($post['date']) ?></span>
                        <span><?= $readingTime ?> min de leitura</span>
                        <span><?= $commentsCount ?> <?= $commentsCount === 1 ? 'comentário' : 'comentários' ?></span>
                    </div>
                </div>

                <div class="publication-actions">
                    <button type="button" class="publication-action">Curtir</button>
                    <button type="button" class="publication-action">Salvar</button>
                    <button type="button" class="publication-action">Compartilhar</button>
                </div>
            </header>

            <figure class="publication-hero-image">
                <img src="<?= htmlspecialchars($category['cover'] ?? '') ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                <figcaption>Foto: Acervo O Editorial</figcaption>
            </figure>

            <div class="publication-body">
                <?php foreach ($contentParagraphs as $paragraph): ?>
                    <p><?= nl2br(htmlspecialchars($paragraph)) ?></p>
                <?php endforeach; ?>
            </div>

            <div class="publication-bottom-meta">
                <?php if ($category): ?>
                    <div class="publication-category-chip">
                        <span>Categoria</span>
                        <a class="post-tag post-tag-<?= htmlspecialchars($category['tag_class']) ?>" href="<?= htmlspecialchars($routeUrl('categoria', ['slug' => $post['category']])) ?>">
                            <?= htmlspecialchars($category['name']) ?>
                        </a>
                    </div>
                <?php endif; ?>

                <button type="button" class="btn-secondary">Compartilhar artigo</button>
            </div>

            <section class="publication-author-box">
                <div>
                    <h2><?= htmlspecialchars($post['author']) ?></h2>
                    <p>Jornalista do O Editorial.</p>
                </div>
            </section>
        </article>

        <?php if (!empty($relatedPosts)): ?>
            <section class="publication-related-section">
                <h2>Publicações relacionadas</h2>
                <div class="publication-related-grid">
                    <?php foreach (array_slice($relatedPosts, 0, 3) as $relatedPost): ?>
                        <?php $relatedCategory = $categories[$relatedPost['category']] ?? $category; ?>
                        <article class="publication-related-card">
                            <a class="publication-related-media" href="<?= htmlspecialchars($routeUrl('publicacao', ['slug' => $relatedPost['slug']])) ?>">
                                <img src="<?= htmlspecialchars($relatedCategory['cover'] ?? '') ?>" alt="<?= htmlspecialchars($relatedPost['title']) ?>">
                            </a>
                            <div class="publication-related-body">
                                <a class="post-tag post-tag-<?= htmlspecialchars($relatedCategory['tag_class'] ?? 'tecnologia') ?>" href="<?= htmlspecialchars($routeUrl('categoria', ['slug' => $relatedPost['category']])) ?>">
                                    <?= htmlspecialchars($relatedCategory['name'] ?? 'Categoria') ?>
                                </a>
                                <h3><a href="<?= htmlspecialchars($routeUrl('publicacao', ['slug' => $relatedPost['slug']])) ?>"><?= htmlspecialchars($relatedPost['title']) ?></a></h3>
                                <span><?= htmlspecialchars($relatedPost['date']) ?></span>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

        <section class="publication-comments-section" id="comentarios">
            <div class="publication-comments-header">
                <h2>Comentários (<?= $commentsCount ?>)</h2>
                <p>Participe da conversa com sua conta pública do portal.</p>
            </div>

            <div class="publication-comments-list">
                <?php if (empty($postComments)): ?>
                    <div class="publication-comment-empty">
                        Ainda não há comentários nesta publicação.
                    </div>
                <?php else: ?>
                    <?php foreach ($postComments as $comment): ?>
                        <article class="publication-comment-card">
                            <div class="publication-comment-body">
                                <div class="publication-comment-meta">
                                    <strong><?= htmlspecialchars($comment['autor']) ?></strong>
                                    <span><?= htmlspecialchars($comment['data']) ?></span>
                                </div>
                                <p><?= nl2br(htmlspecialchars($comment['texto'] ?? $comment['trecho'])) ?></p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="publication-comment-form-shell">
                <h3>Deixe seu comentário</h3>
                <?php if (!$commentsEnabled): ?>
                    <p>Os comentários estão temporariamente desativados para esta publicação.</p>
                <?php elseif ($isPublicUserLogged): ?>
                    <p>Comentando como <strong><?= htmlspecialchars($loggedReaderName) ?></strong>.</p>
                    <form action="<?= htmlspecialchars($routeUrl('publicacao/comentar')) ?>" method="POST" class="publication-comment-form">
                        <input type="hidden" name="slug" value="<?= htmlspecialchars($post['slug']) ?>">
                        <textarea name="comentario" rows="5" placeholder="Escreva sua opinião sobre a publicação..." required></textarea>
                        <div class="publication-comment-actions">
                            <button type="submit" class="btn-primary">Publicar comentário</button>
                        </div>
                    </form>
                <?php else: ?>
                    <p>Você precisa entrar com uma conta pública para comentar nesta publicação.</p>
                    <a class="btn-primary" href="<?= htmlspecialchars($routeUrl('login', ['modo' => 'entrar'])) ?>">Entrar / Cadastrar</a>
                <?php endif; ?>
            </div>
        </section>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>
