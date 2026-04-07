<?php $currentRoute = $_GET['url'] ?? 'home'; ?>
        </main>
    </div>

    <?php if (str_starts_with($currentRoute, 'admin/')): ?>
        <footer class="main-footer">
            <div class="container" style="padding: 20px 0 32px; text-align: center; color: #5f6f8d;">
                &copy; 2026 O Editorial. Ambiente administrativo de protótipo.
            </div>
        </footer>
    <?php else: ?>
        <footer class="public-footer">
            <div class="container public-footer-main">
                <div class="footer-column">
                    <a class="footer-brand" href="<?= htmlspecialchars($routeUrl('home')) ?>">
                        <span class="brand-badge">OE</span>
                        <span>O Editorial</span>
                    </a>
                    <p>Jornalismo com profundidade e compromisso.</p>
                    <p>O Editorial é um veículo jornalístico independente comprometido com a qualidade informativa e o pluralismo de ideias.</p>
                </div>

                <div class="footer-column">
                    <h4>Categorias</h4>
                    <div class="footer-links">
                        <a href="<?= htmlspecialchars($routeUrl('home')) ?>#tecnologia">Tecnologia</a>
                        <a href="<?= htmlspecialchars($routeUrl('home')) ?>#politica">Política</a>
                        <a href="<?= htmlspecialchars($routeUrl('home')) ?>#ciencia">Ciência</a>
                        <a href="<?= htmlspecialchars($routeUrl('home')) ?>#meio-ambiente">Meio Ambiente</a>
                        <a href="<?= htmlspecialchars($routeUrl('home')) ?>#cultura">Cultura</a>
                    </div>
                </div>

                <div class="footer-column">
                    <h4>Contato</h4>
                    <p>contato@oeditorial.com.br</p>
                    <h4>Newsletter</h4>
                    <form class="footer-newsletter" action="<?= htmlspecialchars($routeUrl('login', ['modo' => 'criar'])) ?>" method="get">
                        <input type="email" name="newsletter" placeholder="Seu e-mail" aria-label="Seu e-mail">
                        <button type="submit" class="btn-primary">OK</button>
                    </form>
                </div>
            </div>

            <div class="container public-footer-bottom">
                <span>&copy; 2026 O Editorial. Todos os direitos reservados.</span>
                <div class="footer-legal">
                    <a href="<?= htmlspecialchars($routeUrl('login', ['modo' => 'criar'])) ?>">Política de Privacidade</a>
                    <a href="<?= htmlspecialchars($routeUrl('login', ['modo' => 'criar'])) ?>">Termos de Uso</a>
                    <a href="mailto:contato@oeditorial.com.br">Fale Conosco</a>
                </div>
            </div>
        </footer>
    <?php endif; ?>
</div>
</body>
</html>
