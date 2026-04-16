<?php
$settings = $portalData['settings'] ?? [];
$allComments = array_values($portalData['comments'] ?? []);
$allPosts = array_values($portalData['posts'] ?? []);
$readerName = trim((string) ($_SESSION['usuario_publico_nome'] ?? 'Leitor O Editorial'));
$readerEmail = trim((string) ($_SESSION['usuario_publico_email'] ?? 'leitor@oeditorial.com.br'));
$readerComments = array_values(array_filter($allComments, static function (array $comment) use ($readerEmail, $readerName): bool {
    return ($comment['email'] ?? '') === $readerEmail || ($comment['autor'] ?? '') === $readerName;
}));

usort($readerComments, static function (array $left, array $right): int {
    return (int) ($right['id'] ?? 0) <=> (int) ($left['id'] ?? 0);
});

$commentedPostIds = [];
foreach ($readerComments as $readerComment) {
    $commentedPostIds[(int) ($readerComment['post_id'] ?? 0)] = true;
}

$readerCommentedPosts = 0;
foreach ($allPosts as $readerPost) {
    if (isset($commentedPostIds[(int) ($readerPost['id'] ?? 0)])) {
        $readerCommentedPosts++;
    }
}

$accountCreatedAt = $_SESSION['usuario_publico_criado_em'] ?? date('d/m/Y');

include __DIR__ . '/../partials/header.php';
?>

<section class="account-page">
    <div class="container account-container">
        <div class="account-hero">
            <div class="account-hero-copy">
                <span class="account-eyebrow">Minha conta</span>
                <h1>Área do leitor</h1>
                <p>Consulte seus dados de acesso e acompanhe sua participação nas discussões do portal.</p>
            </div>
            <a class="btn-secondary account-logout-button" href="<?= htmlspecialchars($routeUrl('logout-publico')) ?>">Sair da conta</a>
        </div>

        <div class="account-grid">
            <section class="account-card account-profile-card">
                <div class="account-profile-top">
                    <div>
                        <h2><?= htmlspecialchars($readerName) ?></h2>
                        <p><?= htmlspecialchars($readerEmail) ?></p>
                    </div>
                </div>

                <dl class="account-profile-meta">
                    <div>
                        <dt>Status</dt>
                        <dd>Conta ativa</dd>
                    </div>
                    <div>
                        <dt>Membro desde</dt>
                        <dd><?= htmlspecialchars($accountCreatedAt) ?></dd>
                    </div>
                    <div>
                        <dt>Perfil</dt>
                        <dd>Leitor cadastrado</dd>
                    </div>
                </dl>
            </section>

            <section class="account-card account-stats-card">
                <div class="account-stat">
                    <strong><?= count($readerComments) ?></strong>
                    <span><?= count($readerComments) === 1 ? 'Comentário publicado' : 'Comentários publicados' ?></span>
                </div>
                <div class="account-stat">
                    <strong><?= $readerCommentedPosts ?></strong>
                    <span><?= $readerCommentedPosts === 1 ? 'Publicação comentada' : 'Publicações comentadas' ?></span>
                </div>
                <div class="account-stat">
                    <strong><?= !empty($settings['exibir_comentarios']) ? 'On' : 'Off' ?></strong>
                    <span>Comentários no portal</span>
                </div>
            </section>
        </div>

        <section class="account-card account-comments-card">
            <div class="account-section-header">
                <div>
                    <h2>Seus comentários recentes</h2>
                </div>
                <a class="btn-neutral" href="<?= htmlspecialchars($routeUrl('publicacoes')) ?>">Explorar publicações</a>
            </div>

            <?php if (empty($readerComments)): ?>
                <div class="account-empty-state">
                    <h3>Você ainda não comentou em nenhuma publicação.</h3>
                    <p>Entre em uma matéria do portal e participe da conversa para começar seu histórico.</p>
                </div>
            <?php else: ?>
                <div class="account-comments-list">
                    <?php foreach (array_slice($readerComments, 0, 5) as $readerComment): ?>
                        <?php
                        $commentPost = null;
                        foreach ($allPosts as $readerPost) {
                            if ((int) ($readerPost['id'] ?? 0) === (int) ($readerComment['post_id'] ?? 0)) {
                                $commentPost = $readerPost;
                                break;
                            }
                        }
                        ?>
                        <article class="account-comment-item">
                            <div class="account-comment-header">
                                <strong><?= htmlspecialchars($commentPost['title'] ?? 'Publicação do portal') ?></strong>
                                <span><?= htmlspecialchars($readerComment['data'] ?? '') ?></span>
                            </div>
                            <p><?= nl2br(htmlspecialchars($readerComment['texto'] ?? $readerComment['trecho'] ?? '')) ?></p>
                            <?php if ($commentPost): ?>
                                <a class="account-comment-link" href="<?= htmlspecialchars($routeUrl('publicacao', ['slug' => $commentPost['slug']])) ?>#comentarios">Ver publicação</a>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>
