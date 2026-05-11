<?php

class Database
{
    public static function connect(string $rootPath): PDO
    {
        self::loadEnv($rootPath . '/.env');

        if (! extension_loaded('pdo_mysql')) {
            throw new RuntimeException('A extensão pdo_mysql não está ativa no PHP. Instale/ative o pacote php-mysql antes de abrir a aplicação.');
        }

        $host     = getenv('DB_HOST') ?: '127.0.0.1';
        $port     = getenv('DB_PORT') ?: '3306';
        $database = getenv('DB_DATABASE') ?: 'portal_editorial';
        $username = getenv('DB_USERNAME') ?: 'root';
        $password = getenv('DB_PASSWORD');
        $password = $password === false ? '' : $password;

        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $host, $port, $database);

        try {
            return new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $exception) {
            throw new RuntimeException(
                'Não foi possível conectar ao banco MySQL. Verifique se o serviço está ativo, se o banco portal_editorial foi criado e se o arquivo .env está correto.',
                0,
                $exception
            );
        }
    }

    private static function loadEnv(string $path): void
    {
        if (! is_file($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#') || ! str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);
            $key           = trim($key);
            $value         = trim($value);
            $value         = trim($value, "\"'");

            if ($key !== '' && getenv($key) === false) {
                putenv($key . '=' . $value);
                $_ENV[$key] = $value;
            }
        }
    }
}
