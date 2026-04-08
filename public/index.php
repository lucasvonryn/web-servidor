@ -1,70 +1,92 @@
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
$basePath = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
$basePath = $basePath === '.' ? '' : $basePath;

$routeUrl = static function (string $route = 'home', array $params = []) use ($basePath): string {
    $query = http_build_query(array_merge(['url' => $route], $params));
    return ($basePath ?: '') . '/index.php' . ($query ? '?' . $query : '');
};

$assetUrl = static function (string $path) use ($basePath): string {
    return ($basePath ?: '') . '/' . ltrim($path, '/');
};

$url = $_GET['url'] ?? 'home';
$viewsPath = __DIR__ . '/../app/Views/';

switch ($url) {
    case 'home':
        include $viewsPath . 'public/home.php';
        break;

    case 'login':
        include $viewsPath . 'public/login.php';
        break;

    case 'admin/login':
        include $viewsPath . 'admin/login.php';
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

    case 'admin/posts/salvar':
        $_SESSION['alerta'] = ['tipo' => 'success', 'mensagem' => 'Postagem salva com sucesso!'];
        header('Location: index.php?url=admin/posts');
        exit;

    case 'processar-login-publico':
        $email = trim($_POST['email'] ?? '');
        $senha = trim($_POST['senha'] ?? '');

        $erros = [];

        if ($email === '') {
            $erros['email'] = 'Informe seu e-mail.';
        }

        if ($senha === '') {
            $erros['senha'] = 'Informe sua senha.';
        }

        $usuarioPublico = [
            'email' => 'leitor@oeditorial.com.br',
            'senha' => '123456',
            'nome' => 'Leitor O Editorial',
        ];

        if (!$erros && ($email !== $usuarioPublico['email'] || $senha !== $usuarioPublico['senha'])) {
            $erros['email'] = 'E-mail ou senha incorretos.';
        if ($email === '') { $erros['email'] = 'Informe seu e-mail.'; }
        if ($senha === '') { $erros['senha'] = 'Informe sua senha.'; }

        // Dados para comparação
        $adminEmail = 'admin@admin.com';
        $adminSenha = '123456';
        
        $leitorEmail = 'leitor@oeditorial.com.br';
        $leitorSenha = '123456';

        if (!$erros) {
            // 1. Tenta logar como ADMIN
            if ($email === $adminEmail && $senha === $adminSenha) {
                $_SESSION['usuario_logado'] = true;
                $_SESSION['usuario_nome'] = 'Administrador';
                $_SESSION['alerta'] = ['tipo' => 'success', 'mensagem' => 'Bem-vindo ao Painel!'];
                header('Location: index.php?url=admin/posts');
                exit;
            } 
            // 2. Tenta logar como LEITOR
            elseif ($email === $leitorEmail && $senha === $leitorSenha) {
                $_SESSION['usuario_publico_logado'] = true;
                $_SESSION['usuario_public_nome'] = 'Leitor O Editorial';
                $_SESSION['alerta'] = ['tipo' => 'success', 'mensagem' => 'Login realizado com sucesso!'];
                header('Location: index.php?url=home');
                exit;
            } 
            // 3. Falhou em ambos
            else {
                $erros['email'] = 'E-mail ou senha incorretos.';
            }
        }

        if ($erros) {
@ -73,6 +95,7 @@ switch ($url) {
            header('Location: index.php?url=login&modo=entrar');
            exit;
        }
<<<<<<< Updated upstream

        $_SESSION['usuario_publico_logado'] = true;
        $_SESSION['usuario_publico_nome'] = $usuarioPublico['nome'];
@ -83,92 +106,129 @@
        header('Location: index.php?url=home');
        exit;

=======
        break;
>>>>>>> Stashed changes
    case 'processar-cadastro-publico':
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $senha = trim($_POST['senha'] ?? '');
        $confirmarSenha = trim($_POST['confirmar_senha'] ?? '');

        $erros = [];

        if ($nome === '') {
            $erros['nome'] = 'Informe seu nome completo.';
        }

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erros['email'] = 'Informe um e-mail válido.';
        }

        if (strlen($senha) < 6) {
            $erros['senha'] = 'A senha deve ter pelo menos 6 caracteres.';
        }

        if ($confirmarSenha !== $senha || $confirmarSenha === '') {
            $erros['confirmar_senha'] = 'As senhas precisam ser iguais.';
        }

        if ($erros) {
            $_SESSION['erros_publico'] = $erros;
            $_SESSION['old_publico'] = [
                'nome' => $nome,
                'email' => $email,
            ];
            header('Location: index.php?url=login&modo=criar');
            exit;
        }

        $_SESSION['usuario_publico_logado'] = true;
        $_SESSION['usuario_publico_nome'] = $nome;
        $_SESSION['alerta'] = [
            'tipo' => 'success',
            'mensagem' => 'Conta criada com sucesso. O protótipo já considera você conectado.'
        ];
        header('Location: index.php?url=home');
        exit;

    case 'processar-login':
        $emailDigitado = trim($_POST['email'] ?? '');
        $senhaDigitada = trim($_POST['senha'] ?? '');

        $usuarioCorreto = 'admin@admin.com';
        $senhaCorreta = '123456';

        if ($emailDigitado === $usuarioCorreto && $senhaDigitada === $senhaCorreta) {
            $_SESSION['usuario_logado'] = true;
            $_SESSION['usuario_nome'] = 'Administrador';
            $_SESSION['alerta'] = ['tipo' => 'success', 'mensagem' => 'Bem-vindo ao Painel!'];

            header('Location: index.php?url=admin/posts');
            exit;
        }

        $_SESSION['erros']['email'] = 'E-mail ou senha incorretos!';
        $_SESSION['old']['email'] = $emailDigitado;
        header('Location: index.php?url=admin/login');
        exit;

    case 'admin/configuracoes/salvar':
        $nome = $_POST['nome_site'] ?? '';

        if (empty($nome)) {
            $_SESSION['alerta'] = [
                'tipo' => 'danger',
                'mensagem' => 'Erro: o nome do portal não pode estar vazio!'
            ];
            header('Location: index.php?url=admin/configuracoes');
            exit;
        }

        $_SESSION['alerta'] = [
            'tipo' => 'success',
            'mensagem' => 'Configurações atualizadas com sucesso!'
        ];

        header('Location: index.php?url=admin/configuracoes');
        exit;
<<<<<<< Updated upstream

    default:
        http_response_code(404);
        echo "<h1>Página não encontrada!</h1><a href='index.php'>Voltar para Home</a>";
=======
        // Rota para Usuários (Equipe)
    case 'admin/usuarios':
        include $viewsPath . 'admin/usuarios/lista.php';
>>>>>>> Stashed changes
        break;
    case 'admin/usuarios/salvar':
        // No protótipo, apenas simulamos o sucesso e redirecionamos
        $_SESSION['alerta'] = [
            'tipo' => 'success', 
            'mensagem' => 'Membro da equipe salvo com sucesso!'
        ];
        header('Location: index.php?url=admin/usuarios');
        exit;

    // Rota para Salvar Categorias
    case 'admin/categorias':
        include $viewsPath . 'admin/categorias/lista.php';
        break;
    case 'admin/categorias/salvar':
        // Lógica simples de redirecionamento para o protótipo
        $_SESSION['alerta'] = ['tipo' => 'success', 'mensagem' => 'Categoria salva com sucesso!'];
        header('Location: index.php?url=admin/categorias');
        exit;

    // Rota para Comentários
    case 'admin/comentarios':
        include $viewsPath . 'admin/comentarios/lista.php';
        break;
default:
        http_response_code(404);
        echo "<h1>404 - Página não encontrada</h1>";
        echo "URL atual: " . htmlspecialchars($url);
        break;
}
} 