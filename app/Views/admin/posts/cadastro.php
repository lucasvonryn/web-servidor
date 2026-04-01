<?php include __DIR__ . '/../../partials/header.php'; ?>

<div class="container" style="margin-top: 30px; max-width: 900px;">
    <nav class="breadcrumb" style="margin-bottom: 20px; font-size: 0.9rem;">
        <a href="index.php?url=admin/posts" style="text-decoration: none; color: #3498db;">📋 Gerenciar Posts</a> 
        <span style="margin: 0 10px; color: #bdc3c7;">&raquo;</span> 
        <span style="font-weight: bold; color: #2c3e50;">Nova Postagem</span>
    </nav>

    <section style="background: #fff; padding: 40px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border: 1px solid #edf2f7;">
        <div style="border-bottom: 2px solid #f1f3f5; margin-bottom: 30px; padding-bottom: 10px;">
            <h2 style="color: #2c3e50; margin: 0;">Criar Nova Publicação</h2>
            <p style="color: #95a5a6; font-size: 0.9rem;">Preencha os campos abaixo para publicar um novo artigo no portal.</p>
        </div>

        <form action="index.php?url=admin/posts/salvar" method="POST">
            
            <div style="margin-bottom: 25px;">
                <label for="titulo" style="display: block; font-weight: 600; color: #34495e; margin-bottom: 8px;">Título da Postagem</label>
                <input type="text" name="titulo" id="titulo" 
                       placeholder="Ex: As novidades do PHP em 2026"
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s;"
                       value="<?= $_SESSION['old']['titulo'] ?? '' ?>" required>
                <?php if(isset($_SESSION['erros']['titulo'])): ?>
                    <small style="color: #e74c3c; font-weight: bold;"><?= $_SESSION['erros']['titulo'] ?></small>
                <?php endif; ?>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
                <div>
                    <label for="categoria" style="display: block; font-weight: 600; color: #34495e; margin-bottom: 8px;">Categoria</label>
                    <select name="categoria" id="categoria" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; background: white;">
                        <option value="">Selecione uma categoria...</option>
                        <option value="Tecnologia">Tecnologia</option>
                        <option value="Design">Design</option>
                        <option value="Educação">Educação</option>
                        <option value="Geral">Geral</option>
                    </select>
                </div>
                <div>
                    <label for="status" style="display: block; font-weight: 600; color: #34495e; margin-bottom: 8px;">Status de Publicação</label>
                    <select name="status" id="status" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; background: white;">
                        <option value="Rascunho">Rascunho (Privado)</option>
                        <option value="Publicado">Publicado (Visível)</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 30px;">
                <label for="conteudo" style="display: block; font-weight: 600; color: #34495e; margin-bottom: 8px;">Conteúdo do Artigo</label>
                <textarea name="conteudo" id="conteudo" rows="12" 
                          placeholder="Comece a escrever seu texto aqui..."
                          style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 8px; font-family: inherit; font-size: 1rem; resize: vertical;"><?= $_SESSION['old']['conteudo'] ?? '' ?></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 15px; border-top: 2px solid #f1f3f5; padding-top: 25px;">
                <a href="index.php?url=admin/posts" style="padding: 12px 25px; color: #7f8c8d; text-decoration: none; font-weight: 600; border-radius: 8px; transition: background 0.2s;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='transparent'">
                    Cancelar
                </a>
                <button type="submit" class="btn-primary" style="padding: 12px 35px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 1rem; box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);">
                    Publicar Postagem
                </button>
            </div>
        </form>
    </section>
</div>

<?php 
// Limpa os dados temporários para não aparecerem em uma nova visita
unset($_SESSION['erros']);
unset($_SESSION['old']);
include __DIR__ . '/../../partials/header.php'; 
?>