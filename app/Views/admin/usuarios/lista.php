<?php
$portalData = $portalData ?? require __DIR__ . '/../../../Data/portal_content.php';
$users = $portalData['users'];

$usuariosAtivos = 0;
$adminUsers = 0;
$editorUsers = 0;
foreach ($users as $usuarioEquipe) {
    if (($usuarioEquipe['status'] ?? '') === 'Ativo') {
        $usuariosAtivos++;
    }
    if (($usuarioEquipe['papel'] ?? '') === 'Administrador') {
        $adminUsers++;
    } else {
        $editorUsers++;
    }
}

include __DIR__ . '/../../partials/header.php';
?>

<section class="admin-page-shell" data-admin-users-page>
    <header class="admin-page-header">
        <div>
            <h1>Usuários da Equipe</h1>
            <div class="admin-breadcrumb">Painel <span>&rsaquo;</span> Usuários</div>
        </div>
        <a href="<?= htmlspecialchars($routeUrl('admin/usuarios/novo')) ?>" class="admin-primary-button">+ Novo usuário</a>
    </header>

    <div class="admin-toolbar">
        <label class="admin-search">
            <span>&#8981;</span>
            <input type="search" placeholder="Buscar usuário..." data-admin-users-search>
        </label>
    </div>

    <div class="admin-stats-grid admin-stats-grid-4">
        <article class="admin-stat-card">
            <strong><?= count($users) ?></strong>
            <span>Total</span>
        </article>
        <article class="admin-stat-card admin-stat-card-success">
            <strong><?= $usuariosAtivos ?></strong>
            <span>Ativos</span>
        </article>
        <article class="admin-stat-card admin-stat-card-accent">
            <strong><?= $adminUsers ?></strong>
            <span>Admins</span>
        </article>
        <article class="admin-stat-card admin-stat-card-info">
            <strong><?= $editorUsers ?></strong>
            <span>Editores</span>
        </article>
    </div>

    <p class="admin-results-copy" data-admin-users-count><?= count($users) ?> usuários encontrados</p>

    <section class="admin-table-card" data-admin-users-page-size="5">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>Papel</th>
                    <th>Status</th>
                    <th>Criado em</th>
                    <th class="admin-table-actions">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $usuarioEquipe): ?>
                    <?php
                    $partes = preg_split('/\s+/', trim($usuarioEquipe['nome'])) ?: [];
                    $iniciais = strtoupper(substr($partes[0] ?? 'U', 0, 1) . substr($partes[1] ?? '', 0, 1));
                    $funcao = ($usuarioEquipe['papel'] ?? '') === 'Administrador' ? 'admin-badge-accent' : 'admin-badge-info';
                    $status = ($usuarioEquipe['status'] ?? '') === 'Ativo' ? 'admin-badge-success' : 'admin-badge-muted';
                    ?>
                    <tr data-admin-user-row data-name="<?= htmlspecialchars(strtolower($usuarioEquipe['nome'])) ?>" data-email="<?= htmlspecialchars(strtolower($usuarioEquipe['email'])) ?>" data-role="<?= htmlspecialchars(strtolower($usuarioEquipe['papel'])) ?>" data-status="<?= htmlspecialchars(strtolower($usuarioEquipe['status'])) ?>">
                        <td>
                            <div class="admin-table-user">
                                <span class="admin-user-avatar"><?= htmlspecialchars($iniciais) ?></span>
                                <div>
                                    <strong><?= htmlspecialchars($usuarioEquipe['nome']) ?></strong>
                                    <span><?= htmlspecialchars($usuarioEquipe['email']) ?></span>
                                </div>
                            </div>
                        </td>
                        <td><span class="admin-badge <?= $funcao ?>"><?= htmlspecialchars($usuarioEquipe['papel']) ?></span></td>
                        <td><span class="admin-badge <?= $status ?>"><?= htmlspecialchars($usuarioEquipe['status']) ?></span></td>
                        <td><?= htmlspecialchars($usuarioEquipe['created_at'] ?? '—') ?></td>
                        <td class="admin-table-actions">
                            <a href="<?= htmlspecialchars($routeUrl('admin/usuarios/editar', ['id' => $usuarioEquipe['id']])) ?>" class="admin-icon-action" aria-label="Editar">✎</a>
                            <a href="<?= htmlspecialchars($routeUrl('admin/usuarios/excluir', ['id' => $usuarioEquipe['id']])) ?>" class="admin-icon-action admin-icon-action-danger" aria-label="Excluir" onclick="return confirm('Deseja excluir este usuário?')">🗑</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <div class="admin-pagination" data-admin-users-pagination></div>
</section>

<?php include __DIR__ . '/../../partials/footer.php'; ?>
