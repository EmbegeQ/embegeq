<?php

declare(strict_types=1);

namespace Embegeq\Database\Schema;

use PDO;

class Schema
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(string $table, callable $callback): void
    {
        $blueprint = new Blueprint($table, 'create');
        $callback($blueprint);

        $sql = $blueprint->toSql();
        $this->pdo->exec($sql);
    }

    public function table(string $table, callable $callback): void
    {
        $blueprint = new Blueprint($table, 'alter');
        $callback($blueprint);

        $statements = $blueprint->toAlterSql();
        foreach ($statements as $sql) {
            $this->pdo->exec($sql);
        }
    }

    public function drop(string $table): void
    {
        $this->pdo->exec("DROP TABLE IF EXISTS {$table}");
    }

    public function dropIfExists(string $table): void
    {
        $this->pdo->exec("DROP TABLE IF EXISTS {$table}");
    }

    public function rename(string $from, string $to): void
    {
        $this->pdo->exec("ALTER TABLE {$from} RENAME TO {$to}");
    }

    public function hasTable(string $table): bool
    {
        $stmt = $this->pdo->prepare("
            SELECT name FROM sqlite_master
            WHERE type='table' AND name=?
        ");
        $stmt->execute([$table]);
        return $stmt->fetch() !== false;
    }

    public function hasColumn(string $table, string $column): bool
    {
        $stmt = $this->pdo->prepare("PRAGMA table_info({$table})");
        $stmt->execute();
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($columns as $col) {
            if ($col['name'] === $column) {
                return true;
            }
        }

        return false;
    }

    public function getColumns(string $table): array
    {
        $stmt = $this->pdo->prepare("PRAGMA table_info({$table})");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function disableForeignKeyConstraints(): void
    {
        $this->pdo->exec('PRAGMA foreign_keys = OFF');
    }

    public function enableForeignKeyConstraints(): void
    {
        $this->pdo->exec('PRAGMA foreign_keys = ON');
    }
}
