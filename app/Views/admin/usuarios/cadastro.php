<?php include __DIR__ . '/../../partials/header.php'; ?>

<div class="container" style="margin-top: 30px; max-width: 900px;">
    <nav class="breadcrumb" style="margin-bottom: 20px; font-size: 0.9rem;">
        <a href="index.php?url=admin/users" style="text-decoration: none; color: #3498db;">👥 Gerenciar Equipe</a> 
        <span style="margin: 0 10px; color: #bdc3c7;">&raquo;</span> 
        <span style="font-weight: bold; color: #2c3e50;">Novo Integrante</span>
    </nav>

    <section style="background: #fff; padding: 40px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border: 1px solid #edf2f7;">
        <div style="border-bottom: 2px solid #f1f3f5; margin-bottom: 30px; padding-bottom: 10px;">
            <h2 style="color: #2c3e50; margin: 0;">Cadastrar Novo Usuário</h2>
            <p style="color: #95a5a6; font-size: 0.9rem;">Defina as credenciais e o nível de acesso para o novo membro da equipe.</p>
        </div>

        <form action="index.php?url=admin/usuarios/salvar" method="POST">
            
            <div style="margin-bottom: 25px;">
                <label for="nome" style="display: block; font-weight: 600; color: #34495e; margin-bottom: 8px;">Nome Completo</label>
                <input type="text" name="nome" id="nome" 
                       placeholder="Ex: Julio Silva"
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
                <div>
                    <label for="email" style="display: block; font-weight: 600; color: #34495e; margin-bottom: 8px;">E-mail de Acesso</label>
                    <input type="email" name="email" id="email" 
                           placeholder="usuario@email.com"
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;" required>
                </div>
                <div>
                    <label for="papel" style="display: block; font-weight: 600; color: #34495e; margin-bottom: 8px;">Nível de Permissão</label>
                    <select name="papel" id="papel" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; background: white; cursor: pointer;">
                        <option value="admin">Administrador (Acesso Total)</option>
                        <option value="editor">Editor (Apenas Publicações)</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 30px;">
                <label for="senha" style="display: block; font-weight: 600; color: #34495e; margin-bottom: 8px;">Senha Temporária</label>
                <input type="password" name="senha" id="senha" 
                       placeholder="Mínimo 6 caracteres"
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;" required>
                <small style="color: #7f8c8d; display: block; margin-top: 5px;">O usuário poderá alterar essa senha após o primeiro acesso.</small>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 15px; border-top: 2px solid #f1f3f5; padding-top: 25px;">
                <a href="index.php?url=admin/users" style="padding: 12px 25px; color: #7f8c8d; text-decoration: none; font-weight: 600; border-radius: 8px; transition: background 0.2s;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='transparent'">
                    Cancelar
                </a>
                <button type="submit" class="btn-primary" style="padding: 12px 35px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 1rem; box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);">
                    Confirmar Cadastro
                </button>
            </div>
        </form>
    </section>
</div>

<?php include __DIR__ . '/../../partials/footer.php'; ?>