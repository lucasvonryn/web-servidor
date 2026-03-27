<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container-admin">
    <h2>Configurações Gerais do Site</h2>
    <p>Ajuste as informações que aparecem publicamente no seu portal.</p>

    <form action="index.php?url=admin/configuracoes/salvar" method="POST" class="form-config">
        
        <fieldset style="border: 1px solid #ddd; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            <legend style="font-weight: bold; padding: 0 10px;">Identidade do Site</legend>
            
            <div class="form-group">
                <label for="nome_site">Nome do Portal:</label>
                <input type="text" name="nome_site" id="nome_site" class="form-control" 
                       value="<?= $_SESSION['dados_config']['nome_site'] ?? 'Meu Portal Editorial' ?>">
                <?php if (isset($_SESSION['erros']['nome_site'])): ?>
                    <small style="color: red;"><?= $_SESSION['erros']['nome_site'] ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="slogan">Slogan / Descrição:</label>
                <input type="text" name="slogan" id="slogan" class="form-control" 
                       value="<?= $_SESSION['dados_config']['slogan'] ?? 'As melhores notícias em PHP' ?>">
            </div>
        </fieldset>

        <fieldset style="border: 1px solid #ddd; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            <legend style="font-weight: bold; padding: 0 10px;">Preferências de Exibição</legend>
            
            <div class="form-group">
                <label>Itens por página na Home:</label>
                <select name="itens_home" class="form-control" style="width: 100px;">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="20">20</option>
                </select>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="exibir_comentarios" value="1" checked> 
                    Permitir novos comentários nas publicações
                </label>
            </div>
        </fieldset>

        <div class="form-group">
            <label for="texto_rodape">Texto do Rodapé:</label>
            <textarea name="texto_rodape" id="texto_rodape" class="form-control" rows="3"><?= $_SESSION['dados_config']['texto_rodape'] ?? '© 2024 Todos os direitos reservados.' ?></textarea>
        </div>

        <div style="margin-top: 20px;">
            <button type="submit" class="btn-primary">Salvar Alterações</button>
            <a href="index.php?url=admin/posts" style="margin-left: 10px; color: #666; text-decoration: none;">Cancelar</a>
        </div>
    </form>
</div>

<?php 
// Limpa erros após exibir
unset($_SESSION['erros']);
include __DIR__ . '/../partials/footer.php'; 
?>