<?php include __DIR__ . '/../partials/header.php'; ?>

<section class="container-admin" style="max-width: 420px; margin: 0 auto;">
    <h2>Acesso administrativo</h2>
    <p>Esta tela permanece exclusiva para a equipe editorial.</p>

    <form action="/index.php?url=processar-login" method="POST">
        <div class="form-group">
            <label for="email">E-mail de usuario</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="admin@admin.com" value="<?= htmlspecialchars($_SESSION['old']['email'] ?? '') ?>">
            <?php if (isset($_SESSION['erros']['email'])): ?>
                <small class="field-error"><?= htmlspecialchars($_SESSION['erros']['email']) ?></small>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="senha">Senha</label>
            <input type="password" name="senha" id="senha" class="form-control" placeholder="123456">
            <?php if (isset($_SESSION['erros']['senha'])): ?>
                <small class="field-error"><?= htmlspecialchars($_SESSION['erros']['senha']) ?></small>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn-secondary">Entrar no painel</button>
    </form>
</section>

<?php
unset($_SESSION['erros'], $_SESSION['old']);
include __DIR__ . '/../partials/footer.php';
?>
