<?php
    $oldAdmin    = $_SESSION['old'] ?? [];
    $adminErrors = $_SESSION['erros'] ?? [];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel | O Editorial</title>
    <link rel="icon" href="<?php echo htmlspecialchars($assetUrl('favicon.svg')) ?>" type="image/svg+xml">
    <link rel="stylesheet" href="<?php echo htmlspecialchars($assetUrl('css/style.css')) ?>">
</head>
<body>
<div class="admin-login-page">
    <div class="admin-login-brand">
        <h1>O Editorial</h1>
        <p>Painel Administrativo</p>
    </div>

    <div class="admin-login-card">
        <form action="<?php echo htmlspecialchars($routeUrl('processar-login')) ?>" method="POST" class="admin-login-form">
            <div class="admin-field admin-field-full">
                <label for="email">E-mail da equipe</label>
                <input type="email" name="email" id="email" placeholder="admin@oeditorial.com.br" value="<?php echo htmlspecialchars($oldAdmin['email'] ?? 'admin@admin.com') ?>">
                <?php if (isset($adminErrors['email'])): ?>
                    <small class="field-error"><?php echo htmlspecialchars($adminErrors['email']) ?></small>
                <?php endif; ?>
            </div>

            <div class="admin-field admin-field-full">
                <label for="senha">Senha</label>
                <input type="password" name="senha" id="senha" placeholder="123456">
            </div>

            <button type="submit" class="admin-dark-button admin-dark-button-full">Entrar no Painel</button>

            <div class="admin-inline-alert">Demo: use "admin@admin.com" e senha "123456".</div>
        </form>
    </div>
</div>
</body>
</html>
<?php unset($_SESSION['erros'], $_SESSION['old']); ?>
