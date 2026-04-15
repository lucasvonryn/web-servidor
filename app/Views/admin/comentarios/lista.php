<?php
$portalData = $portalData ?? require __DIR__ . '/../../../Data/portal_content.php';
$comentarios = $portalData['comentarios'];
$posts = $portalData['posts'];
$postsById = [];
$aprovadosCont = 0;
$pendentesCont = 0;
$rejeitadosCont = 0;
foreach ($posts as $commentPost) {
    $postsById[$commentPost['id']] = $commentPost;
}
foreach ($comentarios as $comentarioInfo) {
    if (($comentarioInfo['status'] ?? '') === 'Aprovado') {
        $aprovadosCont++;
    } elseif (($comentarioInfo['status'] ?? '') === 'Pendente') {
        $pendentesCont++;
    } elseif (($comentarioInfo['status'] ?? '') === 'Rejeitado') {
        $rejeitadosCont++;
    }
}
include __DIR__ . '/../../partials/header.php';
?>

<section class="admin-page-shell" data-admin-comentarios-page data-admin-comentarios-page-size="4">
    <header class="admin-page-header">
        <div>
            <h1>Comentários</h1>
            <div class="admin-breadcrumb">Painel <span>&rsaquo;</span> Comentários</div>
        </div>
    </header>

    <div class="admin-stats-grid admin-stats-grid-4">
        <article class="admin-stat-card">
            <strong><?= count($comentarios) ?></strong>
            <span>Total</span>
        </article>
        <article class="admin-stat-card admin-stat-card-success">
            <strong><?= $aprovadosCont ?></strong>
            <span>Aprovados</span>
        </article>
        <article class="admin-stat-card admin-stat-card-warning">
            <strong><?= $pendentesCont ?></strong>
            <span>Pendentes</span>
        </article>
        <article class="admin-stat-card admin-stat-card-danger">
            <strong><?= $rejeitadosCont ?></strong>
            <span>Rejeitados</span>
        </article>
    </div>

    <?php if ($pendentesCont > 0): ?>
        <div class="admin-warning-banner">
            <div>
                <strong><?= $pendentesCont ?> comentário(s) aguardando moderação</strong>
                <span>Revise e aprove ou rejeite cada comentário abaixo.</span>
            </div>
            <a href="<?= htmlspecialchars($routeUrl('admin/comentarios')) ?>" class="admin-warning-button">Ver pendentes</a>
        </div>
    <?php endif; ?>

    <div class="admin-toolbar admin-toolbar-wide">
        <label class="admin-search">
            <span>&#8981;</span>
            <input type="search" placeholder="Buscar por autor, conteúdo ou e-mail..." data-admin-comentarios-search>
        </label>
        <div class="admin-filter-chips">
            <button type="button" class="admin-filter-chip is-active" data-admin-comentarios-filter="todos">Todos</button>
            <button type="button" class="admin-filter-chip" data-admin-comentarios-filter="aprovado">Aprovado</button>
            <button type="button" class="admin-filter-chip" data-admin-comentarios-filter="pendente">Pendente</button>
            <button type="button" class="admin-filter-chip" data-admin-comentarios-filter="rejeitado">Rejeitado</button>
        </div>
    </div>

    <p class="admin-results-copy" data-admin-comentarios-count><?= count($comentarios) ?> comentários encontrados</p>

    <div class="admin-comment-stack">
        <?php foreach ($comentarios as $comentarioInfo): ?>
            <?php
            $commentPost = $postsById[$comentarioInfo['post_id']] ?? null;
            $comentarioStatus = $comentarioInfo['status'] ?? 'Pendente';
            $commentStatusClass = 'admin-badge-warning';
            if ($comentarioStatus === 'Aprovado') {
                $commentStatusClass = 'admin-badge-success';
            } elseif ($comentarioStatus === 'Rejeitado') {
                $commentStatusClass = 'admin-badge-danger';
            }
            $commentInitials = strtoupper(substr($comentarioInfo['autor'] ?? 'U', 0, 1) . substr((string) preg_replace('/^\S+\s+/', '', $comentarioInfo['autor'] ?? ''), 0, 1));
            ?>
            <article class="admin-comment-card <?= $comentarioStatus === 'Pendente' ? 'is-pending' : '' ?>" data-admin-comment-card data-author="<?= htmlspecialchars(strtolower($comentarioInfo['autor'])) ?>" data-email="<?= htmlspecialchars(strtolower($comentarioInfo['email'])) ?>" data-content="<?= htmlspecialchars(strtolower($comentarioInfo['texto'] ?? $comentarioInfo['trecho'])) ?>" data-status="<?= htmlspecialchars(strtolower($comentarioStatus)) ?>" data-post="<?= htmlspecialchars(strtolower($commentPost['title'] ?? '')) ?>">
                <span class="admin-user-avatar"><?= htmlspecialchars($commentInitials) ?></span>
                <div class="admin-comment-body">
                    <div class="admin-comment-meta">
                        <strong><?= htmlspecialchars($comentarioInfo['autor']) ?></strong>
                        <span><?= htmlspecialchars($comentarioInfo['email']) ?></span>
                        <span class="admin-badge <?= $commentStatusClass ?>"><?= htmlspecialchars($comentarioStatus) ?></span>
                    </div>
                    <p><?= htmlspecialchars($comentarioInfo['texto'] ?? $comentarioInfo['trecho']) ?></p>
                    <div class="admin-comment-submeta">
                        <span><?= htmlspecialchars($comentarioInfo['data']) ?></span>
                        <span>Em: <?= htmlspecialchars($commentPost['title'] ?? 'Publicação removida') ?></span>
                    </div>
                </div>
                <div class="admin-card-actions admin-card-actions-vertical">
                    <?php if ($comentarioStatus !== 'Aprovado'): ?>
                        <a href="<?= htmlspecialchars($routeUrl('admin/comentarios/status', ['id' => $comentarioInfo['id'], 'status' => 'Aprovado'])) ?>" class="admin-icon-action admin-icon-action-success">✓</a>
                    <?php endif; ?>
                    <?php if ($comentarioStatus !== 'Rejeitado'): ?>
                        <a href="<?= htmlspecialchars($routeUrl('admin/comentarios/status', ['id' => $comentarioInfo['id'], 'status' => 'Rejeitado'])) ?>" class="admin-icon-action admin-icon-action-danger">✕</a>
                    <?php endif; ?>
                    <a href="<?= htmlspecialchars($routeUrl('admin/comentarios/excluir', ['id' => $comentarioInfo['id']])) ?>" class="admin-icon-action admin-icon-action-danger" onclick="return confirm('Deseja excluir este comentário?')">🗑</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>

    <div class="admin-pagination" data-admin-comentarios-pagination></div>
</section>

<?php include __DIR__ . '/../../partials/footer.php'; ?>
