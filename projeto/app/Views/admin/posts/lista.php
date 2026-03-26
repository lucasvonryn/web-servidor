<?php include __DIR__ . '/../../partials/header.php'; ?>

<div class="breadcrumb">
    <a href="index.php?url=home">Site Público</a> &raquo; 
    <span>Gerenciar Posts</span>
</div>

<div class="admin-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Gerenciar Publicações</h2>
    <a href="index.php?url=admin/posts/novo" class="btn-primary" style="text-decoration: none; padding: 10px 15px;">+ Nova Postagem</a>
</div>

<div style="overflow-x: auto;">
    <table class="tabela-gerencial" style="width: 100%; border-collapse: collapse; background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <thead>
            <tr style="background: #2c3e50; color: white; text-align: left;">
                <th style="padding: 12px;">ID</th>
                <th style="padding: 12px;">Título</th>
                <th style="padding: 12px;">Categoria</th>
                <th style="padding: 12px;">Status</th>
                <th style="padding: 12px; text-align: center;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Simulação de dados (Já que não temos SQL por enquanto)
            $posts_fake = [
                ['id' => 1, 'titulo' => 'Bem-vindo ao nosso Blog', 'categoria' => 'Geral', 'status' => 'Publicado'],
                ['id' => 2, 'titulo' => 'Novidades de PHP 8', 'categoria' => 'Tecnologia', 'status' => 'Rascunho'],
            ];

            if (empty($posts_fake)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">Nenhuma publicação encontrada.</td>
                </tr>
            <?php else: 
                foreach ($posts_fake as $post): ?>
                <tr style="border-bottom: 1px solid #ddd;">
                    <td style="padding: 12px;"><?= $post['id'] ?></td>
                    <td style="padding: 12px;"><strong><?= htmlspecialchars($post['titulo']) ?></strong></td>
                    <td style="padding: 12px;"><?= $post['categoria'] ?></td>
                    <td style="padding: 12px;">
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; color: white; background: <?= $post['status'] == 'Publicado' ? '#27ae60' : '#f39c12' ?>;">
                            <?= $post['status'] ?>
                        </span>
                    </td>
                    <td style="padding: 12px; text-align: center;">
                        <a href="index.php?url=admin/posts/editar&id=<?= $post['id'] ?>" style="color: #3498db; text-decoration: none; margin-right: 10px;">Editar</a>
                        <a href="index.php?url=admin/posts/excluir&id=<?= $post['id'] ?>" style="color: #e74c3c; text-decoration: none;" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; 
            endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../../partials/footer.php'; ?>