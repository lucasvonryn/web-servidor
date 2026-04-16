<?php
    $portalData     = $portalData ?? require __DIR__ . '/../../../Data/portal_content.php';
    $comments       = $portalData['comments'];
    $posts          = $portalData['posts'];
    $postsById      = [];
    $aprovadosCont  = 0;
    $pendentesCont  = 0;
    $rejeitadosCont = 0;
    foreach ($posts as $commentPost) {
    $postsById[$commentPost['id']] = $commentPost;
    }
    foreach ($comments as $comentarioInfo) {
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

<section class="admin-page-shell" data-admin-comments-page data-admin-comments-page-size="4">
    <div class="admin-stats-grid admin-stats-grid-4">
        <article class="admin-stat-card">
            <strong><?php echo count($comments) ?></strong>
            <span>Total</span>
        </article>
        <article class="admin-stat-card admin-stat-card-success">
            <strong><?php echo $aprovadosCont ?></strong>
            <span>Aprovados</span>
        </article>
        <article class="admin-stat-card admin-stat-card-warning">
            <strong><?php echo $pendentesCont ?></strong>
            <span>Pendentes</span>
        </article>
        <article class="admin-stat-card admin-stat-card-danger">
            <strong><?php echo $rejeitadosCont ?></strong>
            <span>Rejeitados</span>
        </article>
    </div>

    <?php if ($pendentesCont > 0): ?>
        <div class="admin-warning-banner">
            <div>
                <strong><?php echo $pendentesCont ?> comentário(s) aguardando moderação</strong>
                <span>Revise e aprove ou rejeite cada comentário abaixo.</span>
            </div>
            <a href="<?php echo htmlspecialchars($routeUrl('admin/comments')) ?>" class="admin-warning-button">Ver pendentes</a>
        </div>
    <?php endif; ?>

    <div class="admin-toolbar admin-toolbar-wide">
        <label class="admin-search">
            <span><i class="bi bi-search"></i></span>
            <input type="search" placeholder="Buscar por autor, conteúdo ou e-mail..." data-admin-comments-search>
        </label>
        <div class="admin-filter-chips">
            <button type="button" class="admin-filter-chip is-active" data-admin-comments-filter="todos">Todos</button>
            <button type="button" class="admin-filter-chip" data-admin-comments-filter="aprovado">Aprovado</button>
            <button type="button" class="admin-filter-chip" data-admin-comments-filter="pendente">Pendente</button>
            <button type="button" class="admin-filter-chip" data-admin-comments-filter="rejeitado">Rejeitado</button>
        </div>
    </div>

    <p class="admin-results-copy" data-admin-comments-count><?php echo count($comments) ?> comentários encontrados</p>

    <div class="admin-comment-stack">
        <?php foreach ($comments as $comentarioInfo): ?>
            <?php
                $commentPost        = $postsById[$comentarioInfo['post_id']] ?? null;
                $comentarioStatus   = $comentarioInfo['status'] ?? 'Pendente';
                $commentStatusClass = 'admin-badge-warning';
                if ($comentarioStatus === 'Aprovado') {
                    $commentStatusClass = 'admin-badge-success';
                } elseif ($comentarioStatus === 'Rejeitado') {
                    $commentStatusClass = 'admin-badge-danger';
                }
            ?>
            <article class="admin-comment-card <?php echo $comentarioStatus === 'Pendente' ? 'is-pending' : '' ?>" data-admin-comment-card data-author="<?php echo htmlspecialchars(strtolower($comentarioInfo['autor'])) ?>" data-email="<?php echo htmlspecialchars(strtolower($comentarioInfo['email'])) ?>" data-content="<?php echo htmlspecialchars(strtolower($comentarioInfo['texto'] ?? $comentarioInfo['trecho'])) ?>" data-status="<?php echo htmlspecialchars(strtolower($comentarioStatus)) ?>" data-post="<?php echo htmlspecialchars(strtolower($commentPost['title'] ?? '')) ?>">
                <div class="admin-comment-body">
                    <div class="admin-comment-meta">
                        <strong><?php echo htmlspecialchars($comentarioInfo['autor']) ?></strong>
                        <span><?php echo htmlspecialchars($comentarioInfo['email']) ?></span>
                        <span class="admin-badge <?php echo $commentStatusClass ?>"><?php echo htmlspecialchars($comentarioStatus) ?></span>
                    </div>
                    <p><?php echo htmlspecialchars($comentarioInfo['texto'] ?? $comentarioInfo['trecho']) ?></p>
                    <div class="admin-comment-submeta">
                        <span><?php echo htmlspecialchars($comentarioInfo['data']) ?></span>
                        <span>Em: <?php echo htmlspecialchars($commentPost['title'] ?? 'Publicação removida') ?></span>
                    </div>
                </div>
                <div class="admin-card-actions admin-card-actions-vertical">
                    <?php if ($comentarioStatus !== 'Aprovado'): ?>
                        <a href="<?php echo htmlspecialchars($routeUrl('admin/comments/status', ['id' => $comentarioInfo['id'], 'status' => 'Aprovado'])) ?>" class="admin-icon-action admin-icon-action-success" aria-label="Aprovar">
                            <i class="bi bi-check-lg"></i>
                        </a>
                    <?php endif; ?>
                    <?php if ($comentarioStatus !== 'Rejeitado'): ?>
                        <a href="<?php echo htmlspecialchars($routeUrl('admin/comments/status', ['id' => $comentarioInfo['id'], 'status' => 'Rejeitado'])) ?>" class="admin-icon-action admin-icon-action-danger" aria-label="Rejeitar">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo htmlspecialchars($routeUrl('admin/comments/excluir', ['id' => $comentarioInfo['id']])) ?>" class="admin-icon-action admin-icon-action-danger" aria-label="Excluir" onclick="return confirm('Deseja excluir este comentário?')">
                        <i class="bi bi-trash"></i>
                    </a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>

    <div class="admin-pagination" data-admin-comments-pagination></div>
</section>

<?php include __DIR__ . '/../../partials/footer.php'; ?>
