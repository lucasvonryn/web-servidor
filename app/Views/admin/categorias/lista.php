<?php include __DIR__ . '/../../partials/header.php'; ?>

<div class="container" style="margin-top: 30px;">
    <div class="admin-header" style="display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 25px;">
        <div>
            <h2 style="margin: 0; color: #2c3e50;">Categorias</h2>
            <p style="margin: 5px 0 0; color: #95a5a6;">Organize os assuntos do seu portal.</p>
        </div>
        <form action="index.php?url=admin/categorias/salvar" method="POST" style="display: flex; gap: 10px;">
            <input type="text" name="nome" placeholder="Nova Categoria..." style="padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            <button type="submit" class="btn-primary" style="padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer;">Adicionar</button>
        </form>
    </div>

    <div style="background: #fff; border-radius: 8px; shadow: 0 4px 15px rgba(0,0,0,0.05); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8f9fa; text-align: left; border-bottom: 2px solid #eee;">
                    <th style="padding: 15px;">ID</th>
                    <th style="padding: 15px;">Nome da Categoria</th>
                    <th style="padding: 15px; text-align: center;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <tr style="border-bottom: 1px solid #f1f1f1;">
                    <td style="padding: 15px;">1</td>
                    <td style="padding: 15px; font-weight: bold;">Tecnologia</td>
                    <td style="padding: 15px; text-align: center;">
                        <a href="#" style="color: #3498db; text-decoration: none; margin-right: 10px;">Editar</a>
                        <a href="#" style="color: #e74c3c; text-decoration: none;">Excluir</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../../partials/footer.php'; ?>