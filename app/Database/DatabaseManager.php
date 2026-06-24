<?php

declare(strict_types=1);

namespace Embegeq\Database;

use PDO;
use Psr\Container\ContainerInterface;

class DatabaseManager
{
    private ContainerInterface $container;
    private array $connections = [];
    private array $config;

    public function __construct(ContainerInterface $container, array $config = [])
    {
        $this->container = $container;
        $this->config = $config;
    }

    public function connection(string $name = 'default'): PDO
    {
        if (isset($this->connections[$name])) {
            return $this->connections[$name];
        }

        $config = $this->config[$name] ?? $this->config['default'] ?? [];
        $pdo = $this->createConnection($config);

        $this->connections[$name] = $pdo;
        return $pdo;
    }

    public function table(string $table, string $connection = 'default'): QueryBuilder
    {
        $pdo = $this->connection($connection);
        return new QueryBuilder($pdo, $table);
    }

    public function beginTransaction(string $connection = 'default'): bool
    {
        return $this->connection($connection)->beginTransaction();
    }

    public function commit(string $connection = 'default'): bool
    {
        return $this->connection($connection)->commit();
    }

    public function rollback(string $connection = 'default'): bool
    {
        return $this->connection($connection)->rollback();
    }

    public function transaction(callable $callback, string $connection = 'default'): mixed
    {
        $pdo = $this->connection($connection);

        try {
            $pdo->beginTransaction();
            $result = $callback($pdo);
            $pdo->commit();
            return $result;
        } catch (\Exception $e) {
            $pdo->rollback();
            throw $e;
        }
    }

    private function createConnection(array $config): PDO
    {
        $driver = $config['driver'] ?? 'sqlite';

        if ($driver === 'sqlite') {
            $dsn = "sqlite:{$config['database']}";
        } elseif ($driver === 'mysql') {
            $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']}";
        } elseif ($driver === 'pgsql') {
            $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['database']}";
        } else {
            throw new \InvalidArgumentException("Unknown driver: {$driver}");
        }

        $pdo = new PDO($dsn, $config['username'] ?? null, $config['password'] ?? null);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }
}
