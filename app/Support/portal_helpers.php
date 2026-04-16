<?php

function portal_slugify(string $value): string
{
    $value    = trim($value);
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
}

function portal_excerpt(string $content, int $limit = 180): string
{
    $content = trim(preg_replace('/\s+/', ' ', strip_tags($content)) ?? '');
    if (strlen($content) <= $limit) {
        return $content;
    }

    $excerpt   = substr($content, 0, $limit);
    $lastSpace = strrpos($excerpt, ' ');
    if ($lastSpace !== false) {
        $excerpt = substr($excerpt, 0, $lastSpace);
    }

    return rtrim($excerpt, " .,;:!?") . '...';
}

function portal_format_date(?int $timestamp = null): string
{
    $timestamp = $timestamp ?? time();
    $months    = [
        1  => 'jan.',
        2  => 'fev.',
        3  => 'mar.',
        4  => 'abr.',
        5  => 'maio',
        6  => 'jun.',
        7  => 'jul.',
        8  => 'ago.',
        9  => 'set.',
        10 => 'out.',
        11 => 'nov.',
        12 => 'dez.',
    ];

    $day   = (int) date('j', $timestamp);
    $month = $months[(int) date('n', $timestamp)] ?? date('m', $timestamp);
    $year  = date('Y', $timestamp);

    return $day . ' de ' . $month . ' de ' . $year;
}

function portal_set_alert(string $tipo, string $mensagem): void
{
    $_SESSION['alerta'] = [
        'tipo'     => $tipo,
        'mensagem' => $mensagem,
    ];
}

function portal_redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function portal_require_admin(callable $routeUrl): void
{
    if (! empty($_SESSION['usuario_logado'])) {
        return;
    }

    portal_set_alert('danger', 'Faça login para acessar o portal administrativo.');
    portal_redirect($routeUrl('admin/login'));
}

function portal_require_public_user(callable $routeUrl, string $message = 'Entre com sua conta pública para continuar.'): void
{
    if (! empty($_SESSION['usuario_publico_logado']) && ! empty($_SESSION['usuario_publico_nome'])) {
        return;
    }

    portal_set_alert('danger', $message);
    portal_redirect($routeUrl('login', ['modo' => 'entrar']));
}
