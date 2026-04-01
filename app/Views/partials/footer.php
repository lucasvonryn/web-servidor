<?php $currentRoute = $_GET['url'] ?? 'home'; ?>
        </main>
    </div>

    <?php if (str_starts_with($currentRoute, 'admin/')): ?>
        <footer class="main-footer">
            <div class="container" style="padding: 20px 0 32px; text-align: center; color: #5f6f8d;">
                &copy; 2026 O Editorial. Ambiente administrativo de prototipo.
            </div>
        </footer>
    <?php else: ?>
        <footer class="public-footer">
            <div class="container public-footer-main">
                <div class="footer-column">
                    <a class="footer-brand" href="/index.php?url=home">
                        <span class="brand-badge">OE</span>
                        <span>O Editorial</span>
                    </a>
                    <p>Jornalismo com profundidade e compromisso.</p>
                    <p>O Editorial e um veiculo jornalistico independente comprometido com a qualidade informativa e o pluralismo de ideias.</p>
                </div>

                <div class="footer-column">
                    <h4>Categorias</h4>
                    <div class="footer-links">
                        <a href="/index.php?url=home#tecnologia">Tecnologia</a>
                        <a href="/index.php?url=home#politica">Politica</a>
                        <a href="/index.php?url=home#ciencia">Ciencia</a>
                        <a href="/index.php?url=home#meio-ambiente">Meio Ambiente</a>
                        <a href="/index.php?url=home#cultura">Cultura</a>
                    </div>
                </div>

                <div class="footer-column">
                    <h4>Contato</h4>
                    <p>contato@oeditorial.com.br</p>
                    <h4>Newsletter</h4>
                    <form class="footer-newsletter" action="/index.php?url=login&modo=criar" method="get">
                        <input type="hidden" name="url" value="login">
                        <input type="hidden" name="modo" value="criar">
                        <input type="email" name="newsletter" placeholder="Seu e-mail" aria-label="Seu e-mail">
                        <button type="submit" class="btn-primary">OK</button>
                    </form>
                </div>
            </div>

            <div class="container public-footer-bottom">
                <span>&copy; 2026 O Editorial. Todos os direitos reservados.</span>
                <div class="footer-legal">
                    <a href="/index.php?url=login&modo=criar">Politica de Privacidade</a>
                    <a href="/index.php?url=login&modo=criar">Termos de Uso</a>
                    <a href="mailto:contato@oeditorial.com.br">Fale Conosco</a>
                </div>
            </div>
        </footer>
    <?php endif; ?>
</div>
</body>
</html>
