<?php

declare(strict_types=1);

namespace Embegeq\Database\Migrations;

use PDO;
use Psr\Container\ContainerInterface;

class MigrationRunner
{
    private ContainerInterface $container;
    private PDO $pdo;
    private string $migrationsPath;
    private string $tableName = 'migrations';

    public function __construct(ContainerInterface $container, PDO $pdo, string $migrationsPath)
    {
        $this->container = $container;
        $this->pdo = $pdo;
        $this->migrationsPath = $migrationsPath;
    }

    public function run(): int
    {
        $this->createMigrationsTable();
        $executed = 0;

        $migrationFiles = $this->getPendingMigrations();

        foreach ($migrationFiles as $file) {
            if ($this->recordExists($file)) {
                continue;
            }

            $migration = $this->loadMigration($file);
            $migration->up();
            $this->recordMigration($file);
            $executed++;
        }

        return $executed;
    }

    public function rollback(int $steps = 1): int
    {
        $this->createMigrationsTable();
        $rolled = 0;

        $migrations = $this->getExecutedMigrations($steps);

        foreach (array_reverse($migrations) as $migration) {
            $class = $this->loadMigration($migration['migration']);
            $class->down();
            $this->deleteMigration($migration['migration']);
            $rolled++;
        }

        return $rolled;
    }

    public function reset(): int
    {
        $this->createMigrationsTable();
        $all = $this->getAllExecutedMigrations();
        $rolled = 0;

        foreach (array_reverse($all) as $migration) {
            $class = $this->loadMigration($migration['migration']);
            $class->down();
            $this->deleteMigration($migration['migration']);
            $rolled++;
        }

        return $rolled;
    }

    private function createMigrationsTable(): void
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS {$this->tableName} (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                migration TEXT NOT NULL UNIQUE,
                batch INTEGER NOT NULL,
                executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        $this->pdo->exec($sql);
    }

    private function getPendingMigrations(): array
    {
        $files = glob($this->migrationsPath . '/*_*.php') ?: [];
        $pending = [];

        foreach ($files as $file) {
            $name = basename($file, '.php');
            if (!$this->recordExists($name)) {
                $pending[] = $name;
            }
        }

        return $pending;
    }

    private function getExecutedMigrations(int $limit = null): array
    {
        $sql = "SELECT migration, batch FROM {$this->tableName} ORDER BY batch DESC, id DESC";

        if ($limit !== null) {
            $sql .= " LIMIT " . intval($limit);
        }

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getAllExecutedMigrations(): array
    {
        $sql = "SELECT migration FROM {$this->tableName} ORDER BY batch DESC, id DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function recordExists(string $migration): bool
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM {$this->tableName} WHERE migration = ?");
        $stmt->execute([$migration]);
        return (int) $stmt->fetchColumn() > 0;
    }

    private function recordMigration(string $migration): void
    {
        $batch = $this->getNextBatchNumber();
        $stmt = $this->pdo->prepare("INSERT INTO {$this->tableName} (migration, batch) VALUES (?, ?)");
        $stmt->execute([$migration, $batch]);
    }

    private function deleteMigration(string $migration): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->tableName} WHERE migration = ?");
        $stmt->execute([$migration]);
    }

    private function getNextBatchNumber(): int
    {
        $stmt = $this->pdo->query("SELECT MAX(batch) as max_batch FROM {$this->tableName}");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return ((int) ($result['max_batch'] ?? 0)) + 1;
    }

    private function loadMigration(string $name): Migration
    {
        $file = $this->migrationsPath . '/' . $name . '.php';
        require_once $file;

        $class = 'Database\\Migrations\\' . $name;
        return new $class($this->container);
    }
}
