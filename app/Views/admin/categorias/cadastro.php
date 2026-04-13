<?php
$portalData = $portalData ?? require __DIR__ . '/../../../Data/portal_content.php';
$categorySlug = trim($_GET['slug'] ?? '');
$editingCategory = $categorySlug !== '' ? ($portalData['categories'][$categorySlug] ?? null) : null;
include __DIR__ . '/../../partials/header.php';
?>

<section class="admin-page-shell admin-form-shell">
    <div class="admin-modal-card">
        <div class="admin-modal-header">
            <div>
                <h1><?= $editingCategory ? 'Editar categoria' : 'Nova categoria' ?></h1>
                <p><?= $editingCategory ? 'Atualize as informações da editoria.' : 'Cadastre uma nova editoria para o portal.' ?></p>
            </div>
            <a href="<?= htmlspecialchars($routeUrl('admin/categorias')) ?>" class="admin-modal-close">&times;</a>
        </div>

        <form action="<?= htmlspecialchars($routeUrl('admin/categorias/salvar')) ?>" method="POST" class="admin-form-grid">
            <input type="hidden" name="original_slug" value="<?= htmlspecialchars($editingCategory['slug'] ?? '') ?>">
            <div class="admin-field admin-field-full">
                <label for="nome">Nome *</label>
                <input type="text" name="nome" id="nome" placeholder="Ex: Tecnologia" value="<?= htmlspecialchars($editingCategory['name'] ?? '') ?>" required>
            </div>

            <div class="admin-field admin-field-full">
                <label for="slug">Slug *</label>
                <input type="text" name="slug" id="slug" placeholder="ex: tecnologia" value="<?= htmlspecialchars($editingCategory['slug'] ?? '') ?>">
                <small>Usado na URL: `/categoria/...`</small>
            </div>

            <div class="admin-field admin-field-full">
                <label for="description">Descrição</label>
                <textarea name="description" id="description" rows="4" placeholder="Breve descrição da categoria"><?= htmlspecialchars($editingCategory['description'] ?? '') ?></textarea>
            </div>

            <div class="admin-field admin-field-full">
                <label for="accent">Cor de destaque *</label>
                <div class="admin-accent-grid">
                    <label class="admin-accent-option"><input type="radio" name="accent" value="tech" <?= ($editingCategory['accent'] ?? 'tech') === 'tech' ? 'checked' : '' ?>> <span>Indigo</span></label>
                    <label class="admin-accent-option"><input type="radio" name="accent" value="politics" <?= ($editingCategory['accent'] ?? '') === 'politics' ? 'checked' : '' ?>> <span>Vermelho</span></label>
                    <label class="admin-accent-option"><input type="radio" name="accent" value="science" <?= ($editingCategory['accent'] ?? '') === 'science' ? 'checked' : '' ?>> <span>Esmeralda</span></label>
                    <label class="admin-accent-option"><input type="radio" name="accent" value="green" <?= ($editingCategory['accent'] ?? '') === 'green' ? 'checked' : '' ?>> <span>Verde</span></label>
                    <label class="admin-accent-option"><input type="radio" name="accent" value="culture" <?= ($editingCategory['accent'] ?? '') === 'culture' ? 'checked' : '' ?>> <span>Roxo</span></label>
                </div>
            </div>

            <div class="admin-form-actions">
                <a href="<?= htmlspecialchars($routeUrl('admin/categorias')) ?>" class="admin-secondary-button">Cancelar</a>
                <button type="submit" class="admin-primary-button"><?= $editingCategory ? 'Salvar categoria' : 'Criar categoria' ?></button>
            </div>
        </form>
    </div>
</section>

<?php include __DIR__ . '/../../partials/footer.php'; ?>
