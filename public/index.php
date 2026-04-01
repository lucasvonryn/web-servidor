<?php

session_start();

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
        }

        if ($erros) {
            $_SESSION['erros_publico'] = $erros;
            $_SESSION['old_publico'] = ['email' => $email];
            header('Location: index.php?url=login&modo=entrar');
            exit;
        }

        $_SESSION['usuario_publico_logado'] = true;
        $_SESSION['usuario_publico_nome'] = $usuarioPublico['nome'];
        $_SESSION['alerta'] = [
            'tipo' => 'success',
            'mensagem' => 'Login realizado com sucesso. Agora voce pode interagir com a comunidade.'
        ];
        header('Location: index.php?url=home');
        exit;

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
            $erros['email'] = 'Informe um e-mail valido.';
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
            'mensagem' => 'Conta criada com sucesso. O prototipo ja considera voce conectado.'
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
                'mensagem' => 'Erro: O nome do portal nao pode estar vazio!'
            ];
            header('Location: index.php?url=admin/configuracoes');
            exit;
        }

        $_SESSION['alerta'] = [
            'tipo' => 'success',
            'mensagem' => 'Configuracoes atualizadas com sucesso!'
        ];

        header('Location: index.php?url=admin/configuracoes');
        exit;

    default:
        http_response_code(404);
        echo "<h1>Pagina nao encontrada!</h1><a href='index.php'>Voltar para Home</a>";
        break;
}
