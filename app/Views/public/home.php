<?php include __DIR__ . '/../partials/header.php'; ?>

<section class="hero-home">
    <div class="container">
        <div class="hero-home-card">
            <div class="hero-home-grid">
                <div>
                    <span class="eyebrow">Portal publico em prototipo</span>
                    <h2>O Editorial agora tem um acesso proprio para leitores.</h2>
                    <p>Entre para comentar nas publicacoes, salvar pautas favoritas e acompanhar os debates da comunidade sem misturar esse fluxo com o painel administrativo.</p>
                    <div style="display: flex; gap: 12px; flex-wrap: wrap; margin-top: 24px;">
                        <a class="btn-primary" href="/index.php?url=login&modo=entrar">Entrar</a>
                        <a class="btn-neutral" href="/index.php?url=login&modo=criar">Criar conta</a>
                    </div>
                </div>

                <aside class="hero-highlights">
                    <h3>O que este prototipo cobre</h3>
                    <ul>
                        <li>Tela publica de login separada do painel de gestao.</li>
                        <li>Fluxo simulado de cadastro com validacao basica.</li>
                        <li>Header e rodape alinhados com a identidade visual de referencia.</li>
                    </ul>
                </aside>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>
