<?php
$modo = $_GET['modo'] ?? 'entrar';
$modo = $modo === 'criar' ? 'criar' : 'entrar';
$oldPublic = $_SESSION['old_publico'] ?? [];
$errosPublic = $_SESSION['erros_publico'] ?? [];
include __DIR__ . '/../partials/header.php';
?>

<section class="public-auth-page">
    <div class="container">
        <div class="auth-frame">
            <div class="auth-card">
                <div class="auth-tabs">
                    <a href="/index.php?url=login&modo=entrar" class="<?= $modo === 'entrar' ? 'is-active' : '' ?>">Entrar</a>
                    <a href="/index.php?url=login&modo=criar" class="<?= $modo === 'criar' ? 'is-active' : '' ?>">Criar Conta</a>
                </div>

                <div class="auth-body">
                    <div class="auth-brand">OE</div>

                    <?php if ($modo === 'entrar'): ?>
                        <div class="auth-heading">
                            <h1>Bem-vindo de volta</h1>
                            <p>Entre para comentar nas publicacoes.</p>
                        </div>

                        <form action="/index.php?url=processar-login-publico" method="POST" class="form-stack">
                            <div class="field">
                                <label for="email">E-mail</label>
                                <div class="input-shell">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path d="M4 6h16v12H4z"></path>
                                        <path d="M4 8l8 6 8-6"></path>
                                    </svg>
                                    <input type="email" name="email" id="email" placeholder="seu@email.com" value="<?= htmlspecialchars($oldPublic['email'] ?? '') ?>">
                                </div>
                                <?php if (isset($errosPublic['email'])): ?>
                                    <span class="field-error"><?= htmlspecialchars($errosPublic['email']) ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="field">
                                <div class="field-row">
                                    <label for="senha">Senha</label>
                                    <a class="field-link" href="/index.php?url=login&modo=criar">Esqueci minha senha</a>
                                </div>
                                <div class="input-shell">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <rect x="5" y="11" width="14" height="9" rx="2"></rect>
                                        <path d="M8 11V8a4 4 0 0 1 8 0v3"></path>
                                    </svg>
                                    <input type="password" name="senha" id="senha" placeholder="••••••••" data-password-toggle>
                                    <button type="button" class="password-toggle" aria-label="Mostrar senha" data-toggle-button>
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                            <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6z"></path>
                                            <circle cx="12" cy="12" r="2.8"></circle>
                                        </svg>
                                    </button>
                                </div>
                                <?php if (isset($errosPublic['senha'])): ?>
                                    <span class="field-error"><?= htmlspecialchars($errosPublic['senha']) ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="auth-actions">
                                <button type="submit" class="btn-secondary">Entrar</button>
                            </div>

                            <p class="auth-switch">Nao tem conta? <a href="/index.php?url=login&modo=criar">Cadastre-se</a></p>
                        </form>
                    <?php else: ?>
                        <div class="auth-heading">
                            <h1>Crie sua conta</h1>
                            <p>Cadastre-se e participe das discussoes.</p>
                        </div>

                        <form action="/index.php?url=processar-cadastro-publico" method="POST" class="form-stack">
                            <div class="field">
                                <label for="nome">Nome completo</label>
                                <div class="input-shell">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <circle cx="12" cy="8" r="3.5"></circle>
                                        <path d="M5 19a7 7 0 0 1 14 0"></path>
                                    </svg>
                                    <input type="text" name="nome" id="nome" placeholder="Seu nome" value="<?= htmlspecialchars($oldPublic['nome'] ?? '') ?>">
                                </div>
                                <?php if (isset($errosPublic['nome'])): ?>
                                    <span class="field-error"><?= htmlspecialchars($errosPublic['nome']) ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="field">
                                <label for="cadastro-email">E-mail</label>
                                <div class="input-shell">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path d="M4 6h16v12H4z"></path>
                                        <path d="M4 8l8 6 8-6"></path>
                                    </svg>
                                    <input type="email" name="email" id="cadastro-email" placeholder="seu@email.com" value="<?= htmlspecialchars($oldPublic['email'] ?? '') ?>">
                                </div>
                                <?php if (isset($errosPublic['email'])): ?>
                                    <span class="field-error"><?= htmlspecialchars($errosPublic['email']) ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="field">
                                <label for="cadastro-senha">Senha</label>
                                <div class="input-shell">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <rect x="5" y="11" width="14" height="9" rx="2"></rect>
                                        <path d="M8 11V8a4 4 0 0 1 8 0v3"></path>
                                    </svg>
                                    <input type="password" name="senha" id="cadastro-senha" placeholder="Minimo de 6 caracteres" data-password-toggle>
                                    <button type="button" class="password-toggle" aria-label="Mostrar senha" data-toggle-button>
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                            <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6z"></path>
                                            <circle cx="12" cy="12" r="2.8"></circle>
                                        </svg>
                                    </button>
                                </div>
                                <?php if (isset($errosPublic['senha'])): ?>
                                    <span class="field-error"><?= htmlspecialchars($errosPublic['senha']) ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="field">
                                <label for="confirmar_senha">Confirmar senha</label>
                                <div class="input-shell">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <rect x="5" y="11" width="14" height="9" rx="2"></rect>
                                        <path d="M8 11V8a4 4 0 0 1 8 0v3"></path>
                                    </svg>
                                    <input type="password" name="confirmar_senha" id="confirmar_senha" placeholder="Repita a senha" data-password-toggle>
                                </div>
                                <?php if (isset($errosPublic['confirmar_senha'])): ?>
                                    <span class="field-error"><?= htmlspecialchars($errosPublic['confirmar_senha']) ?></span>
                                <?php endif; ?>
                            </div>

                            <p class="terms-copy">Ao se cadastrar voce concorda com os <a href="/index.php?url=login&modo=criar">Termos de Uso</a> e a <a href="/index.php?url=login&modo=criar">Politica de Privacidade</a>.</p>

                            <div class="auth-actions">
                                <button type="submit" class="btn-primary">Criar conta</button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <a class="auth-back" href="/index.php?url=home">
                <span>&larr;</span>
                <span>Voltar ao site</span>
            </a>
        </div>
    </div>
</section>

<script>
document.querySelectorAll('[data-toggle-button]').forEach(function (button) {
    button.addEventListener('click', function () {
        var input = button.parentElement.querySelector('[data-password-toggle]');
        if (!input) return;
        var showing = input.type === 'text';
        input.type = showing ? 'password' : 'text';
        button.setAttribute('aria-label', showing ? 'Mostrar senha' : 'Ocultar senha');
    });
});
</script>

<?php
unset($_SESSION['erros_publico'], $_SESSION['old_publico']);
include __DIR__ . '/../partials/footer.php';
?>
