<?php

namespace App\Controllers;

class AuthController
{
    /** @var callable */
    private $routeUrl;
    private \App\Models\UsersModel $usersModel;
    private \App\Models\PortalRepository $portalRepository;

    
    public function __construct(callable $routeUrl, \App\Models\UsersModel $usersModel, \App\Models\PortalRepository $portalRepository)
    {
        $this->routeUrl         = $routeUrl;
        $this->usersModel       = $usersModel;
        $this->portalRepository = $portalRepository;
    }

    public function loginAdmin(): void
    {
        $emailDigitado = trim($_POST['email'] ?? '');
        $senhaDigitada = trim($_POST['senha'] ?? '');

        if ($emailDigitado === 'admin@admin.com' && $senhaDigitada === '123456') {
            $_SESSION['usuario_logado'] = true;
            $_SESSION['usuario_nome']   = 'Administrador';
            $_SESSION['usuario_email']  = $emailDigitado;
            portal_set_alert('success', 'Bem-vindo ao Painel!');
            portal_redirect(($this->routeUrl)('admin/posts'));
        }

        $_SESSION['erros']['email'] = 'E-mail ou senha incorretos!';
        $_SESSION['old']['email']   = $emailDigitado;
        portal_redirect(($this->routeUrl)('admin/login'));
    }

    public function logoutAdmin(): void
    {
        unset($_SESSION['usuario_logado'], $_SESSION['usuario_nome'], $_SESSION['usuario_email']);
        portal_set_alert('success', 'Logout realizado com sucesso.');
        portal_redirect(($this->routeUrl)('admin/login'));
    }

    public function loginPublico(): void
    {
        $email = trim($_POST['email'] ?? '');
        $senha = trim($_POST['senha'] ?? '');

        $erros = [];
        if ($email === '') {
            $erros['email'] = 'Informe seu e-mail.';
        }
        if ($senha === '') {
            $erros['senha'] = 'Informe sua senha.';
        }

        $adminEmail  = 'admin@admin.com';
        $adminSenha  = '123456';
        $leitorEmail = 'leitor@oeditorial.com.br';
        $leitorSenha = '123456';

        if (! $erros) {
            if ($email === $adminEmail && $senha === $adminSenha) {
                $_SESSION['usuario_logado'] = true;
                $_SESSION['usuario_nome']   = 'Administrador';
                portal_set_alert('success', 'Bem-vindo ao Painel!');
                portal_redirect(($this->routeUrl)('admin/posts'));
            }

            if ($email === $leitorEmail && $senha === $leitorSenha) {
                $_SESSION['usuario_publico_logado']    = true;
                $_SESSION['usuario_publico_nome']      = 'Leitor O Editorial';
                $_SESSION['usuario_publico_email']     = $email;
                $_SESSION['usuario_publico_criado_em'] = $_SESSION['usuario_publico_criado_em'] ?? date('d/m/Y');
                portal_set_alert('success', 'Login realizado com sucesso!');
                portal_redirect(($this->routeUrl)('home'));
            }

            $erros['email'] = 'E-mail ou senha incorretos.';
        }

        if ($erros) {
            $_SESSION['erros_publico'] = $erros;
            portal_redirect(($this->routeUrl)('login', ['modo' => 'entrar']));
        }
    }

    public function cadastroPublico(): void
    {
        $nome           = trim($_POST['nome'] ?? '');
        $email          = trim($_POST['email'] ?? '');
        $senha          = trim($_POST['senha'] ?? '');
        $confirmarSenha = trim($_POST['confirmar_senha'] ?? '');

        $erros = [];
        if ($nome === '') {
            $erros['nome'] = 'Informe seu nome completo.';
        }
        if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erros['email'] = 'Informe um e-mail válido.';
        }
        if (strlen($senha) < 6) {
            $erros['senha'] = 'A senha deve ter pelo menos 6 caracteres.';
        }
        if ($confirmarSenha !== $senha || $confirmarSenha === '') {
            $erros['confirmar_senha'] = 'As senhas precisam ser iguais.';
        }

       
        $portalData = $this->portalRepository->getPortalData();

        
        if (!isset($erros['email']) && $this->usersModel->emailExists($portalData, $email)) {
            $erros['email'] = 'Este e-mail já está cadastrado no sistema.';
        }

        if ($erros) {
            $_SESSION['erros_publico'] = $erros;
            $_SESSION['old_publico']   = [
                'nome'  => $nome,
                'email' => $email,
            ];
            portal_redirect(($this->routeUrl)('login', ['modo' => 'criar']));
        }

        
        try {
            $updatedData = $this->usersModel->createPublicUser($portalData, [
                'nome'  => $nome,
                'email' => $email,
                'senha' => $senha
            ]);
            
            
            $novoId = $updatedData['last_inserted_id'] ?? 0;

            
            $_SESSION['usuario_publico_logado']    = true;
            $_SESSION['usuario_publico_id']        = $novoId;
            $_SESSION['usuario_publico_nome']      = $nome;
            $_SESSION['usuario_publico_email']     = $email;
            $_SESSION['usuario_publico_criado_em'] = date('d/m/Y');

            portal_set_alert('success', 'Conta criada com sucesso e salva no banco de dados!');
            portal_redirect(($this->routeUrl)('home'));

        } catch (\Throwable $e) {
            
            $_SESSION['erros_publico'] = ['geral' => 'Erro ao salvar os dados. Tente novamente.'];
            portal_redirect(($this->routeUrl)('login', ['modo' => 'criar']));
        }
    }

    public function logoutPublico(): void
    {
        unset(
            $_SESSION['usuario_publico_logado'],
            $_SESSION['usuario_publico_nome'],
            $_SESSION['usuario_publico_email'],
            $_SESSION['usuario_publico_criado_em']
        );
        portal_set_alert('success', 'Logout realizado com sucesso.');
        portal_redirect(($this->routeUrl)('home'));
    }
}
