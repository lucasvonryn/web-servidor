<?php

session_start(); // Necessário para as mensagens de feedback

// 1. Captura a página que o usuário quer acessar
$url = $_GET['url'] ?? 'home';

// 2. Define onde as pastas de interface (Views) estão localizadas
$viewsPath = __DIR__ . '/../app/Views/';

// 3. Lógica de Navegação (Roteamento Simples)
switch ($url) {
    case 'home':
        include $viewsPath . 'public/home.php';
        break;

    case 'login':
        include $viewsPath . 'public/login.php';
        break;

    case 'admin/posts':
        include $viewsPath . 'admin/posts/lista.php';
        break;

    case 'admin/posts/novo':
        include $viewsPath . 'admin/posts/cadastro.php';
        break;
        
    case 'admin/configuracoes':
        include $viewsPath . 'admin/configuracoes.php';
        break;
    /** TESTEEEEEEEEEEEEEEEEEEEEEEEEEEEEE */

case 'processar-login':
    $email_digitado = $_POST['email'] ?? '';
    $senha_digitada = $_POST['senha'] ?? '';

    // DEFINIÇÃO DOS DADOS PADRÃO (SIMULADOS)
    $usuario_correto = "admin@admin.com";
    $senha_correta = "123456";

    if ($email_digitado === $usuario_correto && $senha_digitada === $senha_correta) {
        // LOGIN COM SUCESSO
        $_SESSION['usuario_logado'] = true;
        $_SESSION['usuario_nome'] = "Administrador";
        $_SESSION['alerta'] = ['tipo' => 'success', 'mensagem' => 'Bem-vindo ao Painel!'];
        
        header("Location: index.php?url=admin/posts");
        exit;
    } else {
        // FALHA NO LOGIN (Requisito: Feedback de erro)
        $_SESSION['erros']['email'] = "E-mail ou senha incorretos!";
        $_SESSION['old']['email'] = $email_digitado; // Mantém o e-mail no campo
        
        header("Location: index.php?url=login");
        exit;
    }
    break;

    case 'admin/configuracoes/salvar':
    // 1. Simula a recepção dos dados do formulário
    $nome = $_POST['nome_site'] ?? '';

    // 2. Validação simples no Servidor (Requisito Obrigatório)
    if (empty($nome)) {
        $_SESSION['alerta'] = [
            'tipo' => 'danger', 
            'mensagem' => 'Erro: O nome do portal não pode estar vazio!'
        ];
        header("Location: index.php?url=admin/configuracoes");
        exit;
    }

    // 3. Simula o "Salvamento" com sucesso
    $_SESSION['alerta'] = [
        'tipo' => 'success', 
        'mensagem' => 'Configurações atualizadas com sucesso!'
    ];
    
    // Redireciona de volta para a tela de configurações
    header("Location: index.php?url=admin/configuracoes");
    exit;
    break;

    default:
        // Caso a página não exista (Erro 404)
        http_response_code(404);
        echo "<h1>Página não encontrada!</h1><a href='index.php'>Voltar para Home</a>";
        break;
}