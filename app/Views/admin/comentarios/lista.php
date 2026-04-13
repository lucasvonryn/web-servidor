<?php
$portalData = $portalData ?? require __DIR__ . '/../../../Data/portal_content.php';
$comments = $portalData['comments'];
$posts = $portalData['posts'];
$postsById = [];
$approvedCount = 0;
$pendingCount = 0;
$rejectedCount = 0;
foreach ($posts as $commentPost) {
    $postsById[$commentPost['id']] = $commentPost;
}
foreach ($comments as $commentItem) {
    if (($commentItem['status'] ?? '') === 'Aprovado') {
        $approvedCount++;
    } elseif (($commentItem['status'] ?? '') === 'Pendente') {
        $pendingCount++;
    } elseif (($commentItem['status'] ?? '') === 'Rejeitado') {
        $rejectedCount++;
    }
}
include __DIR__ . '/../../partials/header.php';
?>

<section class="admin-page-shell" data-admin-comments-page data-admin-comments-page-size="4">
    <header class="admin-page-header">
        <div>
            <h1>Comentários</h1>
            <div class="admin-breadcrumb">Painel <span>&rsaquo;</span> Comentários</div>
        </div>
    </header>

    <div class="admin-stats-grid admin-stats-grid-4">
        <article class="admin-stat-card">
            <strong><?= count($comments) ?></strong>
            <span>Total</span>
        </article>
        <article class="admin-stat-card admin-stat-card-success">
            <strong><?= $approvedCount ?></strong>
            <span>Aprovados</span>
        </article>
        <article class="admin-stat-card admin-stat-card-warning">
            <strong><?= $pendingCount ?></strong>
            <span>Pendentes</span>
        </article>
        <article class="admin-stat-card admin-stat-card-danger">
            <strong><?= $rejectedCount ?></strong>
            <span>Rejeitados</span>
        </article>
    </div>

    <?php if ($pendingCount > 0): ?>
        <div class="admin-warning-banner">
            <div>
                <strong><?= $pendingCount ?> comentário(s) aguardando moderação</strong>
                <span>Revise e aprove ou rejeite cada comentário abaixo.</span>
            </div>
            <a href="<?= htmlspecialchars($routeUrl('admin/comentarios')) ?>" class="admin-warning-button">Ver pendentes</a>
        </div>
    <?php endif; ?>

    <div class="admin-toolbar admin-toolbar-wide">
        <label class="admin-search">
            <span>&#8981;</span>
            <input type="search" placeholder="Buscar por autor, conteúdo ou e-mail..." data-admin-comments-search>
        </label>
        <div class="admin-filter-chips">
            <button type="button" class="admin-filter-chip is-active" data-admin-comments-filter="todos">Todos</button>
            <button type="button" class="admin-filter-chip" data-admin-comments-filter="aprovado">Aprovado</button>
            <button type="button" class="admin-filter-chip" data-admin-comments-filter="pendente">Pendente</button>
            <button type="button" class="admin-filter-chip" data-admin-comments-filter="rejeitado">Rejeitado</button>
        </div>
    </div>

    <p class="admin-results-copy" data-admin-comments-count><?= count($comments) ?> comentários encontrados</p>

    <div class="admin-comment-stack">
        <?php foreach ($comments as $commentItem): ?>
            <?php
            $commentPost = $postsById[$commentItem['post_id']] ?? null;
            $commentStatus = $commentItem['status'] ?? 'Pendente';
            $commentStatusClass = 'admin-badge-warning';
            if ($commentStatus === 'Aprovado') {
                $commentStatusClass = 'admin-badge-success';
            } elseif ($commentStatus === 'Rejeitado') {
                $commentStatusClass = 'admin-badge-danger';
            }
            $commentInitials = strtoupper(substr($commentItem['autor'] ?? 'U', 0, 1) . substr((string) preg_replace('/^\S+\s+/', '', $commentItem['autor'] ?? ''), 0, 1));
            ?>
            <article class="admin-comment-card <?= $commentStatus === 'Pendente' ? 'is-pending' : '' ?>" data-admin-comment-card data-author="<?= htmlspecialchars(strtolower($commentItem['autor'])) ?>" data-email="<?= htmlspecialchars(strtolower($commentItem['email'])) ?>" data-content="<?= htmlspecialchars(strtolower($commentItem['texto'] ?? $commentItem['trecho'])) ?>" data-status="<?= htmlspecialchars(strtolower($commentStatus)) ?>" data-post="<?= htmlspecialchars(strtolower($commentPost['title'] ?? '')) ?>">
                <span class="admin-user-avatar"><?= htmlspecialchars($commentInitials) ?></span>
                <div class="admin-comment-body">
                    <div class="admin-comment-meta">
                        <strong><?= htmlspecialchars($commentItem['autor']) ?></strong>
                        <span><?= htmlspecialchars($commentItem['email']) ?></span>
                        <span class="admin-badge <?= $commentStatusClass ?>"><?= htmlspecialchars($commentStatus) ?></span>
                    </div>
                    <p><?= htmlspecialchars($commentItem['texto'] ?? $commentItem['trecho']) ?></p>
                    <div class="admin-comment-submeta">
                        <span><?= htmlspecialchars($commentItem['data']) ?></span>
                        <span>Em: <?= htmlspecialchars($commentPost['title'] ?? 'Publicação removida') ?></span>
                    </div>
                </div>
                <div class="admin-card-actions admin-card-actions-vertical">
                    <?php if ($commentStatus !== 'Aprovado'): ?>
                        <a href="<?= htmlspecialchars($routeUrl('admin/comentarios/status', ['id' => $commentItem['id'], 'status' => 'Aprovado'])) ?>" class="admin-icon-action admin-icon-action-success">✓</a>
                    <?php endif; ?>
                    <?php if ($commentStatus !== 'Rejeitado'): ?>
                        <a href="<?= htmlspecialchars($routeUrl('admin/comentarios/status', ['id' => $commentItem['id'], 'status' => 'Rejeitado'])) ?>" class="admin-icon-action admin-icon-action-danger">✕</a>
                    <?php endif; ?>
                    <a href="<?= htmlspecialchars($routeUrl('admin/comentarios/excluir', ['id' => $commentItem['id']])) ?>" class="admin-icon-action admin-icon-action-danger" onclick="return confirm('Deseja excluir este comentário?')">🗑</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>

    <div class="admin-pagination" data-admin-comments-pagination></div>
</section>

<?php include __DIR__ . '/../../partials/footer.php'; ?>
