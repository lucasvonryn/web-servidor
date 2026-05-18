<?php

namespace App\Core;

use Dotenv\Dotenv;
use PDO;
use PDOException;
use RuntimeException;

class Database
{
    public static function connect(string $rootPath): PDO
    {
        self::loadEnv($rootPath);

        if (! extension_loaded('pdo_mysql')) {
            throw new RuntimeException('A extensão pdo_mysql não está ativa no PHP. Instale/ative o pacote php-mysql antes de abrir a aplicação.');
        }

        $host     = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?: '127.0.0.1';
        $port     = $_ENV['DB_PORT'] ?? getenv('DB_PORT') ?: '3306';
        $database = $_ENV['DB_DATABASE'] ?? getenv('DB_DATABASE') ?: 'portal_editorial';
        $username = $_ENV['DB_USERNAME'] ?? getenv('DB_USERNAME') ?: 'root';
        $password = $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD');
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

    private static function loadEnv(string $rootPath): void
    {
        $envPath = $rootPath . '/.env';
        if (! is_file($envPath)) {
            return;
        }

        Dotenv::createImmutable($rootPath)->safeLoad();
    }
}
