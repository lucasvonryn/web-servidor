<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="login-container" style="max-width: 400px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
    <h2 style="text-align: center;">Acesso ao Painel</h2>

    <form action="index.php?url=processar-login" method="POST">
        
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="email">E-mail de Usuário:</label>
            <input type="email" name="email" id="email" class="form-control" 
                   placeholder="exemplo@email.com"
                   value="<?= $_SESSION['old']['email'] ?? '' ?>">
            
            <?php if (isset($_SESSION['erros']['email'])): ?>
                <small style="color: red;"><?= $_SESSION['erros']['email'] ?></small>
            <?php endif; ?>
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" class="form-control">
            
            <?php if (isset($_SESSION['erros']['senha'])): ?>
                <small style="color: red;"><?= $_SESSION['erros']['senha'] ?></small>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn-primary" style="width: 100%;">Entrar no Sistema</button>
    </form>

    <p style="text-align: center; margin-top: 15px;">
        <small>Dica: Utilize o e-mail e senha cadastrados pela equipe.</small>
    </p>
</div>

<?php 
// Limpa os erros específicos após a exibição para não ficarem "presos" na tela
unset($_SESSION['erros']);
unset($_SESSION['old']);
include __DIR__ . '/../partials/footer.php'; 
?>