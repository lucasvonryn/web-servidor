<?php
$portalData = $portalData ?? require __DIR__ . '/../../../Data/portal_content.php';
$userId = (int) ($_GET['id'] ?? 0);
$editingUser = null;
foreach ($portalData['users'] as $userItem) {
    if ((int) ($userItem['id'] ?? 0) === $userId) {
        $editingUser = $userItem;
        break;
    }
}
include __DIR__ . '/../../partials/header.php';
?>

<section class="admin-page-shell admin-form-shell">
    <div class="admin-modal-card">
        <div class="admin-modal-header">
            <div>
                <h1><?= $editingUser ? 'Editar usuário' : 'Novo usuário' ?></h1>
                <p><?= $editingUser ? 'Atualize os dados do membro da equipe.' : 'Cadastre um novo membro da equipe editorial.' ?></p>
            </div>
            <a href="<?= htmlspecialchars($routeUrl('admin/usuarios')) ?>" class="admin-modal-close">&times;</a>
        </div>

        <form action="<?= htmlspecialchars($routeUrl('admin/usuarios/salvar')) ?>" method="POST" class="admin-form-grid">
            <input type="hidden" name="id" value="<?= htmlspecialchars((string) ($editingUser['id'] ?? 0)) ?>">
            <div class="admin-field admin-field-full">
                <label for="nome">Nome completo *</label>
                <input type="text" name="nome" id="nome" placeholder="Nome do usuário" value="<?= htmlspecialchars($editingUser['nome'] ?? '') ?>" required>
            </div>

            <div class="admin-field admin-field-full">
                <label for="email">E-mail *</label>
                <input type="email" name="email" id="email" placeholder="usuario@oeditorial.com.br" value="<?= htmlspecialchars($editingUser['email'] ?? '') ?>" required>
            </div>

            <div class="admin-field">
                <label for="papel">Papel *</label>
                <select name="papel" id="papel">
                    <option value="editor" <?= ($editingUser['papel'] ?? '') !== 'Administrador' ? 'selected' : '' ?>>Editor</option>
                    <option value="admin" <?= ($editingUser['papel'] ?? '') === 'Administrador' ? 'selected' : '' ?>>Administrador</option>
                </select>
            </div>

            <div class="admin-field">
                <label for="status">Status *</label>
                <select name="status" id="status">
                    <option value="Ativo" <?= ($editingUser['status'] ?? 'Ativo') === 'Ativo' ? 'selected' : '' ?>>Ativo</option>
                    <option value="Inativo" <?= ($editingUser['status'] ?? '') === 'Inativo' ? 'selected' : '' ?>>Inativo</option>
                </select>
            </div>

            <div class="admin-field admin-field-full">
                <label for="senha">Senha provisória <?= $editingUser ? '' : '*' ?></label>
                <input type="password" name="senha" id="senha" placeholder="<?= $editingUser ? 'Preencha apenas se quiser redefinir no protótipo' : 'Mínimo de 6 caracteres' ?>" <?= $editingUser ? '' : 'required' ?>>
            </div>

            <div class="admin-inline-alert">Uma senha provisória será enviada apenas no fluxo futuro com banco. No protótipo, ela é usada só para validação de cadastro.</div>

            <div class="admin-form-actions">
                <a href="<?= htmlspecialchars($routeUrl('admin/usuarios')) ?>" class="admin-secondary-button">Cancelar</a>
                <button type="submit" class="admin-primary-button"><?= $editingUser ? 'Salvar alterações' : 'Criar usuário' ?></button>
            </div>
        </form>
    </div>
</section>

<?php include __DIR__ . '/../../partials/footer.php'; ?>
