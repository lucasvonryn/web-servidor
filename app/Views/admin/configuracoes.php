<?php
$portalData = $portalData ?? require __DIR__ . '/../../Data/portal_content.php';
$settings = $portalData['settings'];
include __DIR__ . '/../partials/header.php';
?>

<section class="admin-page-shell">
    <header class="admin-page-header">
        <div>
            <h1>Configurações do Site</h1>
            <div class="admin-breadcrumb">Painel <span>&rsaquo;</span> Configurações</div>
        </div>
    </header>

    <form action="<?= htmlspecialchars($routeUrl('admin/configuracoes/salvar')) ?>" method="POST" class="admin-settings-form">
        <div class="admin-settings-tabs">
            <button type="button" class="admin-tab-button" data-admin-tab="gerais">Informações gerais</button>
            <button type="button" class="admin-tab-button" data-admin-tab="exibicao">Exibição</button>
            <button type="button" class="admin-tab-button" data-admin-tab="contato">Contato e rodapé</button>
        </div>

        <section class="admin-settings-pane is-active" data-admin-pane="gerais">
            <div class="admin-settings-block">
                <h2>Identidade do site</h2>
                <p>Informações básicas exibidas no cabeçalho e no rodapé.</p>

                <div class="admin-field admin-field-full">
                    <label for="nome_site">Nome do site *</label>
                    <input type="text" name="nome_site" id="nome_site" value="<?= htmlspecialchars($settings['nome_site'] ?? '') ?>">
                </div>

                <div class="admin-field admin-field-full">
                    <label for="slogan">Slogan / Tagline</label>
                    <input type="text" name="slogan" id="slogan" value="<?= htmlspecialchars($settings['slogan'] ?? '') ?>">
                </div>

                <div class="admin-field admin-field-full">
                    <label for="about_text">Texto institucional (Sobre)</label>
                    <textarea name="about_text" id="about_text" rows="4"><?= htmlspecialchars($settings['about_text'] ?? '') ?></textarea>
                </div>
            </div>
        </section>

        <section class="admin-settings-pane" data-admin-pane="exibicao">
            <div class="admin-settings-block">
                <h2>Configurações de exibição</h2>
                <p>Controle o que aparece na página inicial e como o conteúdo é exibido.</p>

                <div class="admin-field admin-field-sm">
                    <label for="itens_home">Quantidade de itens na Home *</label>
                    <input type="number" name="itens_home" id="itens_home" min="1" max="20" value="<?= htmlspecialchars((string) ($settings['itens_home'] ?? 6)) ?>">
                </div>

                <div class="admin-toggle-list">
                    <label class="admin-toggle-card"><input type="checkbox" name="show_featured" value="1" <?= !empty($settings['show_featured']) ? 'checked' : '' ?>> <span>Bloco de destaques (carrossel)</span></label>
                    <label class="admin-toggle-card"><input type="checkbox" name="show_latest" value="1" <?= !empty($settings['show_latest']) ? 'checked' : '' ?>> <span>Últimas publicações</span></label>
                    <label class="admin-toggle-card"><input type="checkbox" name="show_categories" value="1" <?= !empty($settings['show_categories']) ? 'checked' : '' ?>> <span>Seção de categorias</span></label>
                    <label class="admin-toggle-card"><input type="checkbox" name="show_newsletter" value="1" <?= !empty($settings['show_newsletter']) ? 'checked' : '' ?>> <span>Bloco de newsletter</span></label>
                    <label class="admin-toggle-card"><input type="checkbox" name="exibir_comentarios" value="1" <?= !empty($settings['exibir_comentarios']) ? 'checked' : '' ?>> <span>Permitir comentários nas publicações</span></label>
                </div>
            </div>
        </section>

        <section class="admin-settings-pane" data-admin-pane="contato">
            <div class="admin-settings-block">
                <h2>Contato e rodapé</h2>
                <p>Informações públicas exibidas no topo e no rodapé do site.</p>

                <div class="admin-field admin-field-full">
                    <label for="contact_email">E-mail de contato público *</label>
                    <input type="email" name="contact_email" id="contact_email" value="<?= htmlspecialchars($settings['contact_email'] ?? 'contato@oeditorial.com.br') ?>">
                </div>

                <div class="admin-field admin-field-full">
                    <label for="texto_rodape">Texto do rodapé (copyright)</label>
                    <input type="text" name="texto_rodape" id="texto_rodape" value="<?= htmlspecialchars($settings['texto_rodape'] ?? '') ?>">
                </div>

                <div class="admin-field admin-field-full">
                    <label for="footer_links">Links do rodapé</label>
                    <input type="text" name="footer_links" id="footer_links" value="<?= htmlspecialchars($settings['footer_links'] ?? '') ?>">
                    <small>Separe os links com o caractere `|`.</small>
                </div>

                <div class="admin-footer-preview">
                    <span><?= htmlspecialchars($settings['texto_rodape'] ?? '') ?></span>
                    <small><?= htmlspecialchars($settings['footer_links'] ?? '') ?></small>
                </div>
            </div>
        </section>

        <div class="admin-settings-savebar">
            <span>As alterações serão aplicadas imediatamente após salvar.</span>
            <button type="submit" class="admin-dark-button">Salvar configurações</button>
        </div>
    </form>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>
