<?php
    $portalData = $portalData ?? require __DIR__ . '/../../../Data/portal_content.php';
    $users      = $portalData['users'];

    $usuariosAtivos = 0;
    $adminUsers     = 0;
    $editorUsers    = 0;
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
        <a href="<?php echo htmlspecialchars($routeUrl('admin/usuarios/novo')) ?>" class="admin-primary-button">+ Novo usuário</a>
    </header>

    <div class="admin-toolbar">
        <label class="admin-search">
            <span><i class="bi bi-search"></i></span>
            <input type="search" placeholder="Buscar usuário..." data-admin-users-search>
        </label>
    </div>

    <div class="admin-stats-grid admin-stats-grid-4">
        <article class="admin-stat-card">
            <strong><?php echo count($users) ?></strong>
            <span>Total</span>
        </article>
        <article class="admin-stat-card admin-stat-card-success">
            <strong><?php echo $usuariosAtivos ?></strong>
            <span>Ativos</span>
        </article>
        <article class="admin-stat-card admin-stat-card-accent">
            <strong><?php echo $adminUsers ?></strong>
            <span>Admins</span>
        </article>
        <article class="admin-stat-card admin-stat-card-info">
            <strong><?php echo $editorUsers ?></strong>
            <span>Editores</span>
        </article>
    </div>

    <p class="admin-results-copy" data-admin-users-count><?php echo count($users) ?> usuários encontrados</p>

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
                        $funcao = ($usuarioEquipe['papel'] ?? '') === 'Administrador' ? 'admin-badge-accent' : 'admin-badge-info';
                        $status = ($usuarioEquipe['status'] ?? '') === 'Ativo' ? 'admin-badge-success' : 'admin-badge-muted';
                    ?>
                    <tr data-admin-user-row data-name="<?php echo htmlspecialchars(strtolower($usuarioEquipe['nome'])) ?>" data-email="<?php echo htmlspecialchars(strtolower($usuarioEquipe['email'])) ?>" data-role="<?php echo htmlspecialchars(strtolower($usuarioEquipe['papel'])) ?>" data-status="<?php echo htmlspecialchars(strtolower($usuarioEquipe['status'])) ?>">
                        <td>
                            <div class="admin-table-user">
                                <div>
                                    <strong><?php echo htmlspecialchars($usuarioEquipe['nome']) ?></strong>
                                    <span><?php echo htmlspecialchars($usuarioEquipe['email']) ?></span>
                                </div>
                            </div>
                        </td>
                        <td><span class="admin-badge <?php echo $funcao ?>"><?php echo htmlspecialchars($usuarioEquipe['papel']) ?></span></td>
                        <td><span class="admin-badge <?php echo $status ?>"><?php echo htmlspecialchars($usuarioEquipe['status']) ?></span></td>
                        <td><?php echo htmlspecialchars($usuarioEquipe['created_at'] ?? '—') ?></td>
                        <td class="admin-table-actions">
                            <a href="<?php echo htmlspecialchars($routeUrl('admin/usuarios/editar', ['id' => $usuarioEquipe['id']])) ?>" class="admin-icon-action" aria-label="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="<?php echo htmlspecialchars($routeUrl('admin/usuarios/excluir', ['id' => $usuarioEquipe['id']])) ?>" class="admin-icon-action admin-icon-action-danger" aria-label="Excluir" onclick="return confirm('Deseja excluir este usuário?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <div class="admin-pagination" data-admin-users-pagination></div>
</section>

<?php include __DIR__ . '/../../partials/footer.php'; ?>
