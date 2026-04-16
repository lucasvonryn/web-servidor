<?php
$portalData = $portalData ?? require __DIR__ . '/../../Data/portal_content.php';
$users = $portalData['users'];
$categories = $portalData['categories'];
$posts = $portalData['posts'];
$comments = $portalData['comments'];

$publishedPosts = 0;
$draftPosts = 0;
$pendingComments = 0;
foreach ($posts as $postItem) {
    if (($postItem['status'] ?? '') === 'Publicado') {
        $publishedPosts++;
    } else {
        $draftPosts++;
    }
}
foreach ($comments as $commentItem) {
    if (($commentItem['status'] ?? '') === 'Pendente') {
        $pendingComments++;
    }
}

$recentPosts = array_slice($posts, 0, 4);
$recentUsers = array_slice($users, 0, 4);
include __DIR__ . '/../partials/header.php';
?>

<section class="admin-page-shell">
    <header class="admin-page-header">
        <div>
            <h1>Painel</h1>
        </div>
    </header>

    <div class="admin-stats-grid">
        <article class="admin-stat-card">
            <strong><?= count($users) ?></strong>
            <span>Usuários da equipe</span>
        </article>
        <article class="admin-stat-card admin-stat-card-success">
            <strong><?= $publishedPosts ?></strong>
            <span>Publicações ativas</span>
        </article>
        <article class="admin-stat-card admin-stat-card-accent">
            <strong><?= count($categories) ?></strong>
            <span>Categorias</span>
        </article>
        <article class="admin-stat-card admin-stat-card-warning">
            <strong><?= $pendingComments ?></strong>
            <span>Comentários pendentes</span>
        </article>
    </div>

    <div class="admin-dashboard-grid">
        <section class="admin-panel-card">
            <div class="admin-panel-card-header">
                <h2>Publicações recentes</h2>
                <a href="<?= htmlspecialchars($routeUrl('admin/posts')) ?>">Ver todas</a>
            </div>
            <div class="admin-list-stack">
                <?php foreach ($recentPosts as $recentPost): ?>
                    <?php $recentCategory = $categories[$recentPost['category']] ?? null; ?>
                    <article class="admin-activity-item">
                        <div>
                            <strong><?= htmlspecialchars($recentPost['title']) ?></strong>
                            <span><?= htmlspecialchars($recentCategory['name'] ?? 'Categoria') ?> • <?= htmlspecialchars($recentPost['date']) ?></span>
                        </div>
                        <span class="admin-badge <?= ($recentPost['status'] ?? '') === 'Publicado' ? 'admin-badge-success' : 'admin-badge-warning' ?>">
                            <?= htmlspecialchars($recentPost['status']) ?>
                        </span>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="admin-panel-card">
            <div class="admin-panel-card-header">
                <h2>Equipe</h2>
                <a href="<?= htmlspecialchars($routeUrl('admin/usuarios')) ?>">Gerenciar</a>
            </div>
            <div class="admin-list-stack">
                <?php foreach ($recentUsers as $recentUser): ?>
                    <article class="admin-user-line">
                        <div>
                            <strong><?= htmlspecialchars($recentUser['nome']) ?></strong>
                            <span><?= htmlspecialchars($recentUser['email']) ?></span>
                        </div>
                        <span class="admin-badge <?= ($recentUser['status'] ?? '') === 'Ativo' ? 'admin-badge-success' : 'admin-badge-muted' ?>">
                            <?= htmlspecialchars($recentUser['status']) ?>
                        </span>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    </div>

    <section class="admin-panel-card">
        <div class="admin-panel-card-header">
            <h2>Resumo do portal</h2>
        </div>
        <div class="admin-summary-grid">
            <div>
                <strong><?= $publishedPosts ?></strong>
                <span>Publicados</span>
            </div>
            <div>
                <strong><?= $draftPosts ?></strong>
                <span>Rascunhos</span>
            </div>
            <div>
                <strong><?= count($comments) ?></strong>
                <span>Comentários</span>
            </div>
            <div>
                <strong><?= count($categories) ?></strong>
                <span>Editorias ativas</span>
            </div>
        </div>
    </section>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>
