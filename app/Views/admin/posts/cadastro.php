<?php
    $portalData  = $portalData ?? require __DIR__ . '/../../../Data/portal_content.php';
    $categories  = $portalData['categories'];
    $postId      = (int) ($_GET['id'] ?? 0);
    $editingPost = null;
    foreach ($portalData['posts'] as $postItem) {
    if ((int) ($postItem['id'] ?? 0) === $postId) {
        $editingPost = $postItem;
        break;
    }
    }
    include __DIR__ . '/../../partials/header.php';
?>

<section class="admin-page-shell admin-form-shell">
    <div class="admin-modal-card admin-modal-card-lg">
        <div class="admin-modal-header">
            <div>
                <h1><?php echo $editingPost ? 'Editar publicação' : 'Nova publicação' ?></h1>
                <p><?php echo $editingPost ? 'Atualize a matéria selecionada.' : 'Crie uma matéria usando os dados dinâmicos do portal.' ?></p>
            </div>
            <a href="<?php echo htmlspecialchars($routeUrl('admin/posts')) ?>" class="admin-modal-close">&times;</a>
        </div>

        <div class="admin-tab-nav">
            <button type="button" class="admin-tab-button" data-admin-tab="informacoes">Informações</button>
            <button type="button" class="admin-tab-button" data-admin-tab="conteudo">Conteúdo</button>
        </div>

        <form action="<?php echo htmlspecialchars($routeUrl('admin/posts/salvar')) ?>" method="POST" class="admin-form-grid">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars((string) ($editingPost['id'] ?? 0)) ?>">
            <div class="admin-tab-pane is-active" data-admin-pane="informacoes">
                <div class="admin-field admin-field-full">
                    <label for="titulo">Título *</label>
                    <input type="text" name="titulo" id="titulo" placeholder="Título da publicação" value="<?php echo htmlspecialchars($_SESSION['old']['titulo'] ?? ($editingPost['title'] ?? '')) ?>" required>
                </div>

                <div class="admin-field admin-field-full">
                    <label for="slug">Slug *</label>
                    <input type="text" name="slug" id="slug" placeholder="url-da-publicacao" value="<?php echo htmlspecialchars($_SESSION['old']['slug'] ?? ($editingPost['slug'] ?? '')) ?>">
                    <small>URL: `/publicacao/...`</small>
                </div>

                <div class="admin-field admin-field-full">
                    <label for="resumo">Resumo *</label>
                    <textarea name="resumo" id="resumo" rows="4" placeholder="Breve descrição da publicação (aparece nos cards)"><?php echo htmlspecialchars($_SESSION['old']['resumo'] ?? ($editingPost['excerpt'] ?? '')) ?></textarea>
                </div>

                <div class="admin-field">
                    <label for="categoria">Categoria *</label>
                    <select name="categoria" id="categoria">
                        <?php foreach ($categories as $slug => $postCategory): ?>
                            <option value="<?php echo htmlspecialchars($slug) ?>" <?php echo ($_SESSION['old']['categoria'] ?? ($editingPost['category'] ?? '')) === $slug ? 'selected' : '' ?>><?php echo htmlspecialchars($postCategory['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="admin-field">
                    <label for="status">Status *</label>
                    <select name="status" id="status">
                        <option value="Rascunho" <?php echo ($_SESSION['old']['status'] ?? ($editingPost['status'] ?? '')) === 'Rascunho' ? 'selected' : '' ?>>Rascunho</option>
                        <option value="Publicado" <?php echo ($_SESSION['old']['status'] ?? ($editingPost['status'] ?? '')) === 'Publicado' ? 'selected' : '' ?>>Publicado</option>
                    </select>
                </div>

                <div class="admin-field">
                    <label for="autor">Autor *</label>
                    <input type="text" name="autor" id="autor" placeholder="Nome do autor" value="<?php echo htmlspecialchars($_SESSION['old']['autor'] ?? ($editingPost['author'] ?? ($_SESSION['usuario_nome'] ?? 'Administrador'))) ?>">
                </div>

                <div class="admin-field">
                    <label for="data_publicacao">Data de publicação *</label>
                    <input type="text" name="data_publicacao" id="data_publicacao" placeholder="25/03/2026" value="<?php echo htmlspecialchars($_SESSION['old']['data_publicacao'] ?? ($editingPost['date'] ?? '')) ?>">
                </div>

                <div class="admin-field admin-field-full">
                    <label for="cover">URL da imagem de capa</label>
                    <input type="text" name="cover" id="cover" placeholder="https://..." value="<?php echo htmlspecialchars($_SESSION['old']['cover'] ?? ($editingPost['cover'] ?? '')) ?>">
                </div>

                <label class="admin-checkbox-line admin-field-full">
                    <input type="checkbox" name="featured" value="1" <?php echo ! empty($_SESSION['old']['featured'] ?? $editingPost['featured'] ?? false) ? 'checked' : '' ?>>
                    <span>Marcar como publicação em destaque na Home</span>
                </label>
            </div>

            <div class="admin-tab-pane" data-admin-pane="conteudo">
                <div class="admin-field admin-field-full">
                    <label for="conteudo">Conteúdo *</label>
                    <textarea name="conteudo" id="conteudo" rows="14" placeholder="Escreva aqui o texto da publicação..." required><?php echo htmlspecialchars($_SESSION['old']['conteudo'] ?? ($editingPost['content'] ?? '')) ?></textarea>
                </div>
            </div>

            <div class="admin-form-actions admin-form-actions-spread">
                <button type="button" class="admin-link-button" data-admin-switch="conteudo">Ir para Conteúdo</button>
                <div class="admin-form-actions-inline">
                    <a href="<?php echo htmlspecialchars($routeUrl('admin/posts')) ?>" class="admin-secondary-button">Cancelar</a>
                    <button type="submit" class="admin-primary-button"><?php echo $editingPost ? 'Salvar publicação' : 'Criar publicação' ?></button>
                </div>
            </div>
        </form>
    </div>
</section>

<?php
    unset($_SESSION['erros'], $_SESSION['old']);
    include __DIR__ . '/../../partials/footer.php';
?>
