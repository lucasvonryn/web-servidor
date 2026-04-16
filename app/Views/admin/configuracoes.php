<?php
    $portalData = $portalData ?? require __DIR__ . '/../../Data/portal_content.php';
    $settings   = $portalData['settings'];
    include __DIR__ . '/../partials/header.php';
?>

<section class="admin-page-shell">
    <form action="<?php echo htmlspecialchars($routeUrl('admin/configuracoes/salvar')) ?>" method="POST" class="admin-settings-form">
        <div class="admin-settings-tabs">
            <button type="button" class="admin-tab-button" data-admin-tab="gerais">Informações gerais</button>
            <button type="button" class="admin-tab-button" data-admin-tab="exibicao">Exibição</button>
            <button type="button" class="admin-tab-button" data-admin-tab="contato">Contato</button>
        </div>

        <section class="admin-settings-pane is-active" data-admin-pane="gerais">
            <div class="admin-settings-block">
                <h2>Identidade do site</h2>
                <p>Informações básicas exibidas no cabeçalho e no rodapé.</p>

                <div class="admin-field admin-field-full">
                    <label for="nome_site">Nome do site *</label>
                    <input type="text" name="nome_site" id="nome_site" value="<?php echo htmlspecialchars($settings['nome_site'] ?? '') ?>">
                </div>

                <div class="admin-field admin-field-full">
                    <label for="slogan">Slogan / Tagline</label>
                    <input type="text" name="slogan" id="slogan" value="<?php echo htmlspecialchars($settings['slogan'] ?? '') ?>">
                </div>

                <div class="admin-field admin-field-full">
                    <label for="about_text">Texto institucional (Sobre)</label>
                    <textarea name="about_text" id="about_text" rows="4"><?php echo htmlspecialchars($settings['about_text'] ?? '') ?></textarea>
                </div>
            </div>
        </section>

        <section class="admin-settings-pane" data-admin-pane="exibicao">
            <div class="admin-settings-block">
                <h2>Configurações de exibição</h2>
                <p>Controle o que aparece na página inicial e como o conteúdo é exibido.</p>

                <div class="admin-field admin-field-sm">
                    <label for="itens_home">Quantidade de itens na Home *</label>
                    <input type="number" name="itens_home" id="itens_home" min="1" max="20" value="<?php echo htmlspecialchars((string) ($settings['itens_home'] ?? 6)) ?>">
                </div>

                <div class="admin-toggle-list">
                    <label class="admin-toggle-card"><input type="checkbox" name="show_featured" value="1" <?php echo ! empty($settings['show_featured']) ? 'checked' : '' ?>> <span>Bloco de destaques (carrossel)</span></label>
                    <label class="admin-toggle-card"><input type="checkbox" name="show_latest" value="1" <?php echo ! empty($settings['show_latest']) ? 'checked' : '' ?>> <span>Últimas publicações</span></label>
                    <label class="admin-toggle-card"><input type="checkbox" name="exibir_comentarios" value="1" <?php echo ! empty($settings['exibir_comentarios']) ? 'checked' : '' ?>> <span>Permitir comentários nas publicações</span></label>
                </div>
            </div>
        </section>

        <section class="admin-settings-pane" data-admin-pane="contato">
            <div class="admin-settings-block">
                <h2>Contato</h2>
                <p>Informações públicas exibidas no topo do site.</p>

                <div class="admin-field admin-field-full">
                    <label for="contact_email">E-mail de contato público *</label>
                    <input type="email" name="contact_email" id="contact_email" value="<?php echo htmlspecialchars($settings['contact_email'] ?? 'contato@oeditorial.com.br') ?>">
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
