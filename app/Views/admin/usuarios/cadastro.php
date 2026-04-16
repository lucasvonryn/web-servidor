<?php
    $portalData  = $portalData ?? require __DIR__ . '/../../../Data/portal_content.php';
    $userId      = (int) ($_GET['id'] ?? 0);
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
                <h1><?php echo $editingUser ? 'Editar usuário' : 'Novo usuário' ?></h1>
                <p><?php echo $editingUser ? 'Atualize os dados do membro da equipe.' : 'Cadastre um novo membro da equipe editorial.' ?></p>
            </div>
            <a href="<?php echo htmlspecialchars($routeUrl('admin/usuarios')) ?>" class="admin-modal-close">&times;</a>
        </div>

        <form action="<?php echo htmlspecialchars($routeUrl('admin/usuarios/salvar')) ?>" method="POST" class="admin-form-grid">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars((string) ($editingUser['id'] ?? 0)) ?>">
            <div class="admin-field admin-field-full">
                <label for="nome">Nome completo *</label>
                <input type="text" name="nome" id="nome" placeholder="Nome do usuário" value="<?php echo htmlspecialchars($editingUser['nome'] ?? '') ?>" required>
            </div>

            <div class="admin-field admin-field-full">
                <label for="email">E-mail *</label>
                <input type="email" name="email" id="email" placeholder="usuario@oeditorial.com.br" value="<?php echo htmlspecialchars($editingUser['email'] ?? '') ?>" required>
            </div>

            <div class="admin-field">
                <label for="papel">Papel *</label>
                <select name="papel" id="papel">
                    <option value="editor" <?php echo ($editingUser['papel'] ?? '') !== 'Administrador' ? 'selected' : '' ?>>Editor</option>
                    <option value="admin" <?php echo ($editingUser['papel'] ?? '') === 'Administrador' ? 'selected' : '' ?>>Administrador</option>
                </select>
            </div>

            <div class="admin-field">
                <label for="status">Status *</label>
                <select name="status" id="status">
                    <option value="Ativo" <?php echo ($editingUser['status'] ?? 'Ativo') === 'Ativo' ? 'selected' : '' ?>>Ativo</option>
                    <option value="Inativo" <?php echo ($editingUser['status'] ?? '') === 'Inativo' ? 'selected' : '' ?>>Inativo</option>
                </select>
            </div>

            <div class="admin-field admin-field-full">
                <label for="senha">Senha provisória <?php echo $editingUser ? '' : '*' ?></label>
                <input type="password" name="senha" id="senha" placeholder="<?php echo $editingUser ? 'Preencha apenas se quiser redefinir no protótipo' : 'Mínimo de 6 caracteres' ?>" <?php echo $editingUser ? '' : 'required' ?>>
            </div>

            <div class="admin-inline-alert">Senha provisória usada só para validação de cadastro.</div>

            <div class="admin-form-actions">
                <a href="<?php echo htmlspecialchars($routeUrl('admin/usuarios')) ?>" class="admin-secondary-button">Cancelar</a>
                <button type="submit" class="admin-primary-button"><?php echo $editingUser ? 'Salvar alterações' : 'Criar usuário' ?></button>
            </div>
        </form>
    </div>
</section>

<?php include __DIR__ . '/../../partials/footer.php'; ?>
