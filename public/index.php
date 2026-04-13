<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

$basePortalData = require __DIR__ . '/../app/Data/portal_content.php';

$slugify = static function (string $value): string {
    $value = trim($value);
    $asciiMap = [
        'á' => 'a', 'à' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a',
        'Á' => 'a', 'À' => 'a', 'Â' => 'a', 'Ã' => 'a', 'Ä' => 'a',
        'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ẽ' => 'e', 'ë' => 'e',
        'É' => 'e', 'È' => 'e', 'Ê' => 'e', 'Ẽ' => 'e', 'Ë' => 'e',
        'í' => 'i', 'ì' => 'i', 'î' => 'i', 'ĩ' => 'i', 'ï' => 'i',
        'Í' => 'i', 'Ì' => 'i', 'Î' => 'i', 'Ĩ' => 'i', 'Ï' => 'i',
        'ó' => 'o', 'ò' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o',
        'Ó' => 'o', 'Ò' => 'o', 'Ô' => 'o', 'Õ' => 'o', 'Ö' => 'o',
        'ú' => 'u', 'ù' => 'u', 'û' => 'u', 'ũ' => 'u', 'ü' => 'u',
        'Ú' => 'u', 'Ù' => 'u', 'Û' => 'u', 'Ũ' => 'u', 'Ü' => 'u',
        'ç' => 'c', 'Ç' => 'c', 'ñ' => 'n', 'Ñ' => 'n',
    ];

    $value = strtr($value, $asciiMap);
    $value = strtolower($value);
    $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? '';
    $value = trim($value, '-');

    return $value !== '' ? $value : 'item';
};

$excerptText = static function (string $content, int $limit = 180): string {
    $content = trim(preg_replace('/\s+/', ' ', strip_tags($content)) ?? '');
    if (strlen($content) <= $limit) {
        return $content;
    }

    $excerpt = substr($content, 0, $limit);
    $lastSpace = strrpos($excerpt, ' ');
    if ($lastSpace !== false) {
        $excerpt = substr($excerpt, 0, $lastSpace);
    }

    return rtrim($excerpt, " .,;:!?") . '...';
};

$formatPortalDate = static function (?int $timestamp = null): string {
    $timestamp = $timestamp ?? time();
    $months = [
        1 => 'jan.',
        2 => 'fev.',
        3 => 'mar.',
        4 => 'abr.',
        5 => 'maio',
        6 => 'jun.',
        7 => 'jul.',
        8 => 'ago.',
        9 => 'set.',
        10 => 'out.',
        11 => 'nov.',
        12 => 'dez.',
    ];

    $day = (int) date('j', $timestamp);
    $month = $months[(int) date('n', $timestamp)] ?? date('m', $timestamp);
    $year = date('Y', $timestamp);

    return $day . ' de ' . $month . ' de ' . $year;
};

$rebuildPortalData = static function (array $data) use ($assetUrl, $basePortalData): array {
    $baseSettings = is_array($basePortalData['settings'] ?? null) ? $basePortalData['settings'] : [];
    $data['settings'] = array_merge($baseSettings, is_array($data['settings'] ?? null) ? $data['settings'] : []);
    $data['categories'] = is_array($data['categories'] ?? null) ? $data['categories'] : [];
    $data['posts'] = array_values(is_array($data['posts'] ?? null) ? $data['posts'] : []);
    $data['users'] = array_values(is_array($data['users'] ?? null) ? $data['users'] : []);
    $data['comments'] = array_values(is_array($data['comments'] ?? null) ? $data['comments'] : []);

    $baseCategories = is_array($basePortalData['categories'] ?? null) ? $basePortalData['categories'] : [];
    $normalizedCategories = [];

    foreach ($baseCategories as $slug => $baseCategory) {
        $sessionCategory = $data['categories'][$slug] ?? null;
        if (is_array($sessionCategory)) {
            $normalizedCategories[$slug] = array_merge($baseCategory, [
                'id' => $sessionCategory['id'] ?? $baseCategory['id'],
                'count' => $sessionCategory['count'] ?? null,
                'count_label' => $sessionCategory['count_label'] ?? null,
            ], ['slug' => $slug]);
            continue;
        }

        foreach ($data['categories'] as $rawCategory) {
            if (!is_array($rawCategory)) {
                continue;
            }

            if (($rawCategory['slug'] ?? null) === $slug) {
                $normalizedCategories[$slug] = array_merge($baseCategory, [
                    'id' => $rawCategory['id'] ?? $baseCategory['id'],
                    'count' => $rawCategory['count'] ?? null,
                    'count_label' => $rawCategory['count_label'] ?? null,
                ], ['slug' => $slug]);
                continue 2;
            }
        }

        $normalizedCategories[$slug] = $baseCategory;
    }

    foreach ($data['categories'] as $key => $rawCategory) {
        if (!is_array($rawCategory)) {
            continue;
        }

        $slug = trim((string) ($rawCategory['slug'] ?? (is_string($key) ? $key : '')));
        if ($slug === '' || isset($normalizedCategories[$slug])) {
            continue;
        }

        $normalizedCategories[$slug] = $rawCategory;
    }

    $data['categories'] = $normalizedCategories;

    usort($data['posts'], static function (array $left, array $right): int {
        return ($right['id'] ?? 0) <=> ($left['id'] ?? 0);
    });

    $defaultCategoryCover = '';
    foreach ($data['categories'] as $category) {
        if (!empty($category['cover'])) {
            $defaultCategoryCover = $category['cover'];
            break;
        }
    }

    if ($defaultCategoryCover === '') {
        $defaultCategoryCover = $assetUrl('assets/home/tecnologia-capa.png');
    }

    $postCounts = [];
    foreach ($data['posts'] as $post) {
        $categorySlug = $post['category'] ?? '';
        if ($categorySlug === '') {
            continue;
        }

        $postCounts[$categorySlug] = ($postCounts[$categorySlug] ?? 0) + 1;
    }

    foreach ($data['categories'] as $slug => &$category) {
        $baseCategory = $baseCategories[$slug] ?? [];
        $count = $postCounts[$slug] ?? 0;
        $category['id'] = $category['id'] ?? ($baseCategory['id'] ?? ($count + 1));
        $category['slug'] = $slug;
        $category['name'] = trim((string) ($category['name'] ?? $baseCategory['name'] ?? $slug));
        $category['tag_class'] = trim((string) ($category['tag_class'] ?? $baseCategory['tag_class'] ?? $slug));
        $category['accent'] = trim((string) ($category['accent'] ?? $baseCategory['accent'] ?? 'tech'));
        $category['cover'] = trim((string) ($category['cover'] ?? $baseCategory['cover'] ?? $defaultCategoryCover));
        $category['description'] = trim((string) ($category['description'] ?? $baseCategory['description'] ?? ''));
        $category['count'] = $count;
        $category['count_label'] = $count . ' ' . ($count === 1 ? 'publicação' : 'publicações');
    }
    unset($category);

    $featuredSlides = array_values(array_filter($data['posts'], static function (array $post): bool {
        return ($post['status'] ?? 'Publicado') === 'Publicado' && !empty($post['featured']);
    }));

    if (empty($featuredSlides)) {
        $featuredSlides = array_values(array_filter($data['posts'], static function (array $post): bool {
            return ($post['status'] ?? 'Publicado') === 'Publicado';
        }));
        $featuredSlides = array_slice($featuredSlides, 0, 3);
    }

    $data['featured_slides'] = $featuredSlides;

    return $data;
};

$sessionPortalData = $_SESSION['portal_data'] ?? null;
$portalData = $rebuildPortalData(is_array($sessionPortalData) ? $sessionPortalData : $basePortalData);
if (is_array($sessionPortalData)) {
    $_SESSION['portal_data'] = $portalData;
}

$persistPortalData = static function (array $data) use (&$portalData, $rebuildPortalData): void {
    $portalData = $rebuildPortalData($data);
    $_SESSION['portal_data'] = $portalData;
};

$ensureAdminLogged = static function () use ($routeUrl): void {
    if (!empty($_SESSION['usuario_logado'])) {
        return;
    }

    $_SESSION['alerta'] = [
        'tipo' => 'danger',
        'mensagem' => 'Faça login para acessar o portal administrativo.',
    ];
    header('Location: ' . $routeUrl('admin/login'));
    exit;
};

$url = $_GET['url'] ?? 'home';
$viewsPath = __DIR__ . '/../app/Views/';

if (strncmp($url, 'admin/', 6) === 0 && $url !== 'admin/login') {
    $ensureAdminLogged();
}

switch ($url) {
    case 'home':
        include $viewsPath . 'public/home.php';
        break;

    case 'login':
        include $viewsPath . 'public/login.php';
        break;

    case 'categoria':
        include $viewsPath . 'public/categoria.php';
        break;

    case 'publicacoes':
        include $viewsPath . 'public/publicacoes.php';
        break;

    case 'publicacao':
        include $viewsPath . 'public/publicacao.php';
        break;

    case 'conta':
        if (empty($_SESSION['usuario_publico_logado']) || empty($_SESSION['usuario_publico_nome'])) {
            $_SESSION['alerta'] = [
                'tipo' => 'danger',
                'mensagem' => 'Entre com sua conta pública para acessar sua área.',
            ];
            header('Location: ' . $routeUrl('login', ['modo' => 'entrar']));
            exit;
        }
        include $viewsPath . 'public/conta.php';
        break;

    case 'admin/login':
        include $viewsPath . 'admin/login.php';
        break;

    case 'admin/painel':
        include $viewsPath . 'admin/painel.php';
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
        $postId = (int) ($_POST['id'] ?? 0);
        $titulo = trim($_POST['titulo'] ?? '');
        $slugInput = trim($_POST['slug'] ?? '');
        $resumo = trim($_POST['resumo'] ?? '');
        $categoria = trim($_POST['categoria'] ?? '');
        $status = trim($_POST['status'] ?? 'Rascunho');
        $autor = trim($_POST['autor'] ?? '');
        $dataPublicacao = trim($_POST['data_publicacao'] ?? '');
        $coverUrl = trim($_POST['cover'] ?? '');
        $featured = !empty($_POST['featured']);
        $conteudo = trim($_POST['conteudo'] ?? '');
        $erros = [];

        if ($titulo === '') {
            $erros['titulo'] = 'Informe o título da publicação.';
        }

        if ($categoria === '' || !isset($portalData['categories'][$categoria])) {
            $erros['categoria'] = 'Selecione uma categoria válida.';
        }

        if ($conteudo === '') {
            $erros['conteudo'] = 'Escreva o conteúdo da publicação.';
        }

        if (!in_array($status, ['Publicado', 'Rascunho'], true)) {
            $status = 'Rascunho';
        }

        if ($autor === '') {
            $autor = trim((string) ($_SESSION['usuario_nome'] ?? 'Administrador'));
        }

        if ($resumo === '') {
            $resumo = $excerptText($conteudo);
        }

        if ($erros) {
            $_SESSION['erros'] = $erros;
            $_SESSION['old'] = [
                'titulo' => $titulo,
                'slug' => $slugInput,
                'resumo' => $resumo,
                'categoria' => $categoria,
                'status' => $status,
                'autor' => $autor,
                'data_publicacao' => $dataPublicacao,
                'cover' => $coverUrl,
                'featured' => $featured,
                'conteudo' => $conteudo,
            ];
            header('Location: ' . $routeUrl($postId > 0 ? 'admin/posts/editar' : 'admin/posts/novo', $postId > 0 ? ['id' => $postId] : []));
            exit;
        }

        $posts = $portalData['posts'];
        $baseSlug = $slugify($slugInput !== '' ? $slugInput : $titulo);
        $slug = $baseSlug;
        $existingSlugs = [];
        foreach ($posts as $existingPost) {
            if ($postId > 0 && (int) ($existingPost['id'] ?? 0) === $postId) {
                continue;
            }
            $existingSlugs[] = $existingPost['slug'];
        }
        $slugSuffix = 2;
        while (in_array($slug, $existingSlugs, true)) {
            $slug = $baseSlug . '-' . $slugSuffix;
            $slugSuffix++;
        }

        $author = $autor;
        $authorParts = preg_split('/\s+/', $author) ?: [];
        $authorShort = $authorParts[0] ?? $author;
        $selectedCategory = $portalData['categories'][$categoria] ?? null;
        $postDate = $dataPublicacao !== '' ? $dataPublicacao : $formatPortalDate();

        $payload = [
            'slug' => $slug,
            'title' => $titulo,
            'excerpt' => $resumo,
            'content' => $conteudo,
            'category' => $categoria,
            'author' => $author,
            'author_short' => $authorShort,
            'date' => $postDate,
            'status' => $status,
            'featured' => $featured,
            'cover' => $coverUrl !== '' ? $coverUrl : ($selectedCategory['cover'] ?? ''),
        ];

        if ($postId > 0) {
            foreach ($posts as &$postItem) {
                if ((int) ($postItem['id'] ?? 0) === $postId) {
                    $postItem = array_merge($postItem, $payload);
                    break;
                }
            }
            unset($postItem);
        } else {
            $nextId = 1;
            foreach ($posts as $post) {
                $nextId = max($nextId, (int) ($post['id'] ?? 0) + 1);
            }
            $posts[] = array_merge(['id' => $nextId], $payload);
        }

        $portalData['posts'] = $posts;
        $persistPortalData($portalData);

        $_SESSION['alerta'] = [
            'tipo' => 'success',
            'mensagem' => $postId > 0
                ? 'Publicação atualizada com sucesso.'
                : ($status === 'Publicado'
                    ? 'Postagem publicada com sucesso e já disponível no portal.'
                    : 'Rascunho salvo com sucesso.'),
        ];
        header('Location: ' . $routeUrl('admin/posts'));
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

        $adminEmail = 'admin@admin.com';
        $adminSenha = '123456';
        $leitorEmail = 'leitor@oeditorial.com.br';
        $leitorSenha = '123456';

        if (!$erros) {
            if ($email === $adminEmail && $senha === $adminSenha) {
                $_SESSION['usuario_logado'] = true;
                $_SESSION['usuario_nome'] = 'Administrador';
                $_SESSION['alerta'] = ['tipo' => 'success', 'mensagem' => 'Bem-vindo ao Painel!'];
                header('Location: ' . $routeUrl('admin/posts'));
                exit;
            }

            if ($email === $leitorEmail && $senha === $leitorSenha) {
                $_SESSION['usuario_publico_logado'] = true;
                $_SESSION['usuario_publico_nome'] = 'Leitor O Editorial';
                $_SESSION['usuario_publico_email'] = $email;
                $_SESSION['usuario_publico_criado_em'] = $_SESSION['usuario_publico_criado_em'] ?? date('d/m/Y');
                $_SESSION['alerta'] = ['tipo' => 'success', 'mensagem' => 'Login realizado com sucesso!'];
                header('Location: ' . $routeUrl('home'));
                exit;
            }

            $erros['email'] = 'E-mail ou senha incorretos.';
        }

        if ($erros) {
            $_SESSION['erros_publico'] = $erros;
            header('Location: ' . $routeUrl('login', ['modo' => 'entrar']));
            exit;
        }
        break;

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
            header('Location: ' . $routeUrl('login', ['modo' => 'criar']));
            exit;
        }

        $_SESSION['usuario_publico_logado'] = true;
        $_SESSION['usuario_publico_nome'] = $nome;
        $_SESSION['usuario_publico_email'] = $email;
        $_SESSION['usuario_publico_criado_em'] = date('d/m/Y');
        $_SESSION['alerta'] = [
            'tipo' => 'success',
            'mensagem' => 'Conta criada com sucesso. O protótipo já considera você conectado.',
        ];
        header('Location: ' . $routeUrl('home'));
        exit;

    case 'logout-publico':
        unset(
            $_SESSION['usuario_publico_logado'],
            $_SESSION['usuario_publico_nome'],
            $_SESSION['usuario_publico_email'],
            $_SESSION['usuario_publico_criado_em']
        );
        $_SESSION['alerta'] = [
            'tipo' => 'success',
            'mensagem' => 'Logout realizado com sucesso.',
        ];
        header('Location: ' . $routeUrl('home'));
        exit;

    case 'publicacao/comentar':
        $postSlug = trim($_POST['slug'] ?? '');
        $redirectUrl = $routeUrl('publicacao', ['slug' => $postSlug]);

        if (empty($_SESSION['usuario_publico_logado']) || empty($_SESSION['usuario_publico_nome'])) {
            $_SESSION['alerta'] = [
                'tipo' => 'danger',
                'mensagem' => 'Você precisa entrar com uma conta pública para comentar.',
            ];
            header('Location: ' . $routeUrl('login', ['modo' => 'entrar']));
            exit;
        }

        if (empty($portalData['settings']['exibir_comentarios'])) {
            $_SESSION['alerta'] = [
                'tipo' => 'danger',
                'mensagem' => 'Os comentários estão desativados no momento.',
            ];
            header('Location: ' . $redirectUrl);
            exit;
        }

        $commentText = trim($_POST['comentario'] ?? '');
        if ($postSlug === '' || $commentText === '') {
            $_SESSION['alerta'] = [
                'tipo' => 'danger',
                'mensagem' => 'Escreva um comentário antes de enviar.',
            ];
            header('Location: ' . $redirectUrl);
            exit;
        }

        $targetPost = null;
        foreach ($portalData['posts'] as $post) {
            if (($post['slug'] ?? '') === $postSlug) {
                $targetPost = $post;
                break;
            }
        }

        if (!$targetPost) {
            http_response_code(404);
            echo '<h1>404 - Publicação não encontrada</h1>';
            exit;
        }

        $comments = $portalData['comments'];
        $nextId = 1;
        foreach ($comments as $comment) {
            $nextId = max($nextId, (int) ($comment['id'] ?? 0) + 1);
        }

        $commentAuthor = trim((string) $_SESSION['usuario_publico_nome']);
        $commentEmail = trim((string) ($_SESSION['usuario_publico_email'] ?? 'leitor@oeditorial.com.br'));
        $commentExcerpt = $excerptText($commentText, 220);

        $comments[] = [
            'id' => $nextId,
            'post_id' => $targetPost['id'],
            'autor' => $commentAuthor,
            'email' => $commentEmail,
            'trecho' => $commentExcerpt,
            'texto' => $commentText,
            'status' => 'Aprovado',
            'data' => $formatPortalDate(),
        ];

        $portalData['comments'] = $comments;
        $persistPortalData($portalData);

        $_SESSION['alerta'] = [
            'tipo' => 'success',
            'mensagem' => 'Comentário publicado com sucesso!',
        ];
        header('Location: ' . $redirectUrl . '#comentarios');
        exit;

    case 'processar-login':
        $emailDigitado = trim($_POST['email'] ?? '');
        $senhaDigitada = trim($_POST['senha'] ?? '');

        if ($emailDigitado === 'admin@admin.com' && $senhaDigitada === '123456') {
            $_SESSION['usuario_logado'] = true;
            $_SESSION['usuario_nome'] = 'Administrador';
            $_SESSION['usuario_email'] = $emailDigitado;
            $_SESSION['alerta'] = ['tipo' => 'success', 'mensagem' => 'Bem-vindo ao Painel!'];
            header('Location: ' . $routeUrl('admin/painel'));
            exit;
        }

        $_SESSION['erros']['email'] = 'E-mail ou senha incorretos!';
        $_SESSION['old']['email'] = $emailDigitado;
        header('Location: ' . $routeUrl('admin/login'));
        exit;

    case 'admin/configuracoes/salvar':
        $nomeSite = trim($_POST['nome_site'] ?? '');
        $slogan = trim($_POST['slogan'] ?? '');
        $aboutText = trim($_POST['about_text'] ?? '');
        $itensHome = (int) ($_POST['itens_home'] ?? 6);
        $contactEmail = trim($_POST['contact_email'] ?? '');
        $footerLinks = trim($_POST['footer_links'] ?? '');
        $textoRodape = trim($_POST['texto_rodape'] ?? '');

        if ($nomeSite === '') {
            $_SESSION['alerta'] = [
                'tipo' => 'danger',
                'mensagem' => 'Erro: o nome do portal não pode estar vazio!',
            ];
            header('Location: ' . $routeUrl('admin/configuracoes'));
            exit;
        }

        $portalData['settings'] = array_merge($portalData['settings'], [
            'nome_site' => $nomeSite,
            'slogan' => $slogan,
            'about_text' => $aboutText,
            'itens_home' => in_array($itensHome, [5, 10, 20], true) ? $itensHome : 6,
            'show_featured' => !empty($_POST['show_featured']),
            'show_latest' => !empty($_POST['show_latest']),
            'show_categories' => !empty($_POST['show_categories']),
            'show_newsletter' => !empty($_POST['show_newsletter']),
            'exibir_comentarios' => !empty($_POST['exibir_comentarios']),
            'contact_email' => $contactEmail !== '' ? $contactEmail : ($portalData['settings']['contact_email'] ?? 'contato@oeditorial.com.br'),
            'footer_links' => $footerLinks,
            'texto_rodape' => $textoRodape !== '' ? $textoRodape : ($portalData['settings']['texto_rodape'] ?? ''),
        ]);
        $persistPortalData($portalData);

        $_SESSION['alerta'] = [
            'tipo' => 'success',
            'mensagem' => 'Configurações atualizadas com sucesso!',
        ];

        header('Location: ' . $routeUrl('admin/configuracoes'));
        exit;

    case 'admin/usuarios':
        include $viewsPath . 'admin/usuarios/lista.php';
        break;

    case 'admin/usuarios/novo':
        include $viewsPath . 'admin/usuarios/cadastro.php';
        break;

    case 'admin/usuarios/editar':
        include $viewsPath . 'admin/usuarios/cadastro.php';
        break;

    case 'admin/usuarios/salvar':
        $userId = (int) ($_POST['id'] ?? 0);
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $papel = trim($_POST['papel'] ?? 'editor');
        $status = trim($_POST['status'] ?? 'Ativo');
        $senha = trim($_POST['senha'] ?? '');

        if ($nome === '' || $email === '' || ($userId <= 0 && $senha === '')) {
            $_SESSION['alerta'] = [
                'tipo' => 'danger',
                'mensagem' => 'Preencha nome, e-mail e senha para cadastrar o integrante.',
            ];
            header('Location: ' . $routeUrl($userId > 0 ? 'admin/usuarios/editar' : 'admin/usuarios/novo', $userId > 0 ? ['id' => $userId] : []));
            exit;
        }

        $users = $portalData['users'];
        if ($userId > 0) {
            foreach ($users as &$user) {
                if ((int) ($user['id'] ?? 0) === $userId) {
                    $user['nome'] = $nome;
                    $user['email'] = $email;
                    $user['papel'] = $papel === 'admin' ? 'Administrador' : 'Editor';
                    $user['status'] = $status === 'Inativo' ? 'Inativo' : 'Ativo';
                    break;
                }
            }
            unset($user);
        } else {
            $nextId = 1;
            foreach ($users as $user) {
                $nextId = max($nextId, (int) ($user['id'] ?? 0) + 1);
            }

            $users[] = [
                'id' => $nextId,
                'nome' => $nome,
                'email' => $email,
                'papel' => $papel === 'admin' ? 'Administrador' : 'Editor',
                'status' => $status === 'Inativo' ? 'Inativo' : 'Ativo',
                'created_at' => date('d/m/Y'),
            ];
        }

        $portalData['users'] = $users;
        $persistPortalData($portalData);

        $_SESSION['alerta'] = [
            'tipo' => 'success',
            'mensagem' => $userId > 0 ? 'Usuário atualizado com sucesso!' : 'Membro da equipe salvo com sucesso!',
        ];
        header('Location: ' . $routeUrl('admin/usuarios'));
        exit;

    case 'admin/usuarios/excluir':
        $userId = (int) ($_GET['id'] ?? 0);
        $users = $portalData['users'];
        $currentAdminEmail = trim((string) ($_SESSION['usuario_email'] ?? ''));
        $removed = false;
        foreach ($users as $index => $user) {
            if ((int) ($user['id'] ?? 0) !== $userId) {
                continue;
            }
            if ($currentAdminEmail !== '' && ($user['email'] ?? '') === $currentAdminEmail) {
                $_SESSION['alerta'] = [
                    'tipo' => 'danger',
                    'mensagem' => 'Você não pode remover o usuário atualmente logado.',
                ];
                header('Location: ' . $routeUrl('admin/usuarios'));
                exit;
            }
            unset($users[$index]);
            $removed = true;
            break;
        }
        if ($removed) {
            $portalData['users'] = array_values($users);
            $persistPortalData($portalData);
            $_SESSION['alerta'] = [
                'tipo' => 'success',
                'mensagem' => 'Usuário removido com sucesso.',
            ];
        }
        header('Location: ' . $routeUrl('admin/usuarios'));
        exit;

    case 'admin/categorias':
        include $viewsPath . 'admin/categorias/lista.php';
        break;

    case 'admin/categorias/novo':
        include $viewsPath . 'admin/categorias/cadastro.php';
        break;

    case 'admin/categorias/editar':
        include $viewsPath . 'admin/categorias/cadastro.php';
        break;

    case 'admin/categorias/salvar':
        $originalSlug = trim($_POST['original_slug'] ?? '');
        $nome = trim($_POST['nome'] ?? '');
        $slugInput = trim($_POST['slug'] ?? '');
        $descricao = trim($_POST['description'] ?? '');
        $accentInput = trim($_POST['accent'] ?? '');

        if ($nome === '') {
            $_SESSION['alerta'] = [
                'tipo' => 'danger',
                'mensagem' => 'Informe o nome da categoria.',
            ];
            header('Location: ' . $routeUrl('admin/categorias'));
            exit;
        }

        $categories = $portalData['categories'];
        $nextId = 1;
        foreach ($categories as $category) {
            $nextId = max($nextId, (int) ($category['id'] ?? 0) + 1);
        }
        $baseSlug = $slugify($slugInput !== '' ? $slugInput : $nome);
        $slug = $baseSlug;
        $slugSuffix = 2;
        while (isset($categories[$slug]) && $slug !== $originalSlug) {
            $slug = $baseSlug . '-' . $slugSuffix;
            $slugSuffix++;
        }

        $accentOptions = ['tech', 'politics', 'science', 'green', 'culture'];
        $accent = in_array($accentInput, $accentOptions, true) ? $accentInput : $accentOptions[($nextId - 1) % count($accentOptions)];
        $fallbackCover = $assetUrl('assets/home/tecnologia-capa.png');
        foreach ($categories as $category) {
            if (!empty($category['cover'])) {
                $fallbackCover = $category['cover'];
                break;
            }
        }

        if ($originalSlug !== '' && isset($categories[$originalSlug])) {
            $currentCategory = $categories[$originalSlug];
            unset($categories[$originalSlug]);
            $categories[$slug] = [
                'id' => $currentCategory['id'] ?? 0,
                'slug' => $slug,
                'name' => $nome,
                'tag_class' => $slug,
                'accent' => $accent,
                'cover' => $currentCategory['cover'] ?? $fallbackCover,
                'description' => $descricao !== '' ? $descricao : ('Conteúdos e análises sobre ' . strtolower($nome) . '.'),
            ];

            foreach ($portalData['posts'] as &$postItem) {
                if (($postItem['category'] ?? '') === $originalSlug) {
                    $postItem['category'] = $slug;
                }
            }
            unset($postItem);
        } else {
            $nextId = 1;
            foreach ($categories as $category) {
                $nextId = max($nextId, (int) ($category['id'] ?? 0) + 1);
            }
            $accent = in_array($accentInput, $accentOptions, true) ? $accentInput : $accentOptions[($nextId - 1) % count($accentOptions)];
            $categories[$slug] = [
                'id' => $nextId,
                'slug' => $slug,
                'name' => $nome,
                'tag_class' => $slug,
                'accent' => $accent,
                'cover' => $fallbackCover,
                'description' => $descricao !== '' ? $descricao : ('Conteúdos e análises sobre ' . strtolower($nome) . '.'),
            ];
        }

        $portalData['categories'] = $categories;
        $persistPortalData($portalData);

        $_SESSION['alerta'] = [
            'tipo' => 'success',
            'mensagem' => $originalSlug !== '' ? 'Categoria atualizada com sucesso!' : 'Categoria salva com sucesso!',
        ];
        header('Location: ' . $routeUrl('admin/categorias'));
        exit;

    case 'admin/categorias/excluir':
        $slug = trim($_GET['slug'] ?? '');
        if ($slug !== '') {
            foreach ($portalData['posts'] as $postItem) {
                if (($postItem['category'] ?? '') === $slug) {
                    $_SESSION['alerta'] = [
                        'tipo' => 'danger',
                        'mensagem' => 'Não é possível excluir uma categoria que ainda possui publicações.',
                    ];
                    header('Location: ' . $routeUrl('admin/categorias'));
                    exit;
                }
            }

            if (isset($portalData['categories'][$slug])) {
                unset($portalData['categories'][$slug]);
                $persistPortalData($portalData);
                $_SESSION['alerta'] = [
                    'tipo' => 'success',
                    'mensagem' => 'Categoria removida com sucesso.',
                ];
            }
        }
        header('Location: ' . $routeUrl('admin/categorias'));
        exit;

    case 'admin/posts/editar':
        include $viewsPath . 'admin/posts/cadastro.php';
        break;

    case 'admin/comentarios':
        include $viewsPath . 'admin/comentarios/lista.php';
        break;

    case 'admin/posts/excluir':
        $postId = (int) ($_GET['id'] ?? 0);
        $posts = $portalData['posts'];
        $removedPost = false;
        foreach ($posts as $index => $postItem) {
            if ((int) ($postItem['id'] ?? 0) === $postId) {
                unset($posts[$index]);
                $removedPost = true;
                break;
            }
        }
        if ($removedPost) {
            $portalData['posts'] = array_values($posts);
            $portalData['comments'] = array_values(array_filter($portalData['comments'], static function (array $commentItem) use ($postId): bool {
                return (int) ($commentItem['post_id'] ?? 0) !== $postId;
            }));
            $persistPortalData($portalData);
            $_SESSION['alerta'] = [
                'tipo' => 'success',
                'mensagem' => 'Publicação removida com sucesso.',
            ];
        }
        header('Location: ' . $routeUrl('admin/posts'));
        exit;

    case 'admin/comentarios/status':
        $commentId = (int) ($_GET['id'] ?? 0);
        $newStatus = trim($_GET['status'] ?? '');
        $allowedStatuses = ['Aprovado', 'Pendente', 'Rejeitado'];
        if ($commentId > 0 && in_array($newStatus, $allowedStatuses, true)) {
            foreach ($portalData['comments'] as &$commentItem) {
                if ((int) ($commentItem['id'] ?? 0) === $commentId) {
                    $commentItem['status'] = $newStatus;
                    break;
                }
            }
            unset($commentItem);
            $persistPortalData($portalData);
            $_SESSION['alerta'] = [
                'tipo' => 'success',
                'mensagem' => 'Status do comentário atualizado.',
            ];
        }
        header('Location: ' . $routeUrl('admin/comentarios'));
        exit;

    case 'admin/comentarios/excluir':
        $commentId = (int) ($_GET['id'] ?? 0);
        $portalData['comments'] = array_values(array_filter($portalData['comments'], static function (array $commentItem) use ($commentId): bool {
            return (int) ($commentItem['id'] ?? 0) !== $commentId;
        }));
        $persistPortalData($portalData);
        $_SESSION['alerta'] = [
            'tipo' => 'success',
            'mensagem' => 'Comentário removido com sucesso.',
        ];
        header('Location: ' . $routeUrl('admin/comentarios'));
        exit;

    default:
        http_response_code(404);
        echo '<h1>404 - Página não encontrada</h1>';
        echo 'URL atual: ' . htmlspecialchars($url);
        break;
}
