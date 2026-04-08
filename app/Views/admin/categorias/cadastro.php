<?php include __DIR__ . '/../../partials/header.php'; ?>

<div class="container" style="margin-top: 30px; max-width: 700px;">
    <nav class="breadcrumb" style="margin-bottom: 20px; font-size: 0.9rem;">
        <a href="index.php?url=admin/categorias" style="text-decoration: none; color: #3498db;">📁 Categorias</a> 
        <span style="margin: 0 10px; color: #bdc3c7;">&raquo;</span> 
        <span style="font-weight: bold; color: #2c3e50;">Nova Categoria</span>
    </nav>

    <section style="background: #fff; padding: 40px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border: 1px solid #edf2f7;">
        <h2 style="color: #2c3e50; margin-bottom: 20px;">Criar Categoria</h2>
        
        <form action="index.php?url=admin/categorias/salvar" method="POST">
            <div style="margin-bottom: 25px;">
                <label for="nome" style="display: block; font-weight: 600; color: #34495e; margin-bottom: 8px;">Nome da Categoria</label>
                <input type="text" name="nome" id="nome" placeholder="Ex: Inteligência Artificial"
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 15px;">
                <button type="submit" class="btn-primary" style="padding: 12px 35px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold;">
                    Cadastrar
                </button>
            </div>
        </form>
    </section>
</div>

<?php include __DIR__ . '/../../partials/footer.php'; ?>