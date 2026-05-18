<?php

namespace App\Core;

class RouteRequest
{
    public static function resolve(string $basePath): string
    {
        if (! empty($_GET['url'])) {
            return trim((string) $_GET['url'], '/');
        }

        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
        $path = str_replace('\\', '/', $path);

        if ($basePath !== '' && str_starts_with($path, $basePath)) {
            $path = substr($path, strlen($basePath)) ?: '/';
        }

        $path = '/' . trim($path, '/');
        if ($path === '/' || $path === '') {
            return 'home';
        }

        $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
        if ($scriptName !== '' && str_ends_with($path, '/' . basename($scriptName))) {
            return 'home';
        }

        return trim($path, '/');
    }
}
