<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página não encontrada — O Editorial</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<div class="page-shell">
    <main class="page-main" style="padding: 48px 24px; text-align: center;">
        <h1>404</h1>
        <p>A página solicitada não foi encontrada.</p>
        <?php if (! empty($route)): ?>
            <p style="color: #7b879d; font-size: 0.9rem;">Rota: <?= htmlspecialchars((string) $route) ?></p>
        <?php endif; ?>
        <p><a href="/">Voltar para a home</a></p>
    </main>
</div>
</body>
</html>
