<?php

declare(strict_types=1);

namespace Embegeq\Database\Seeders;

use PDO;

class TableInserter
{
    private PDO $pdo;
    private string $table;

    public function __construct(PDO $pdo, string $table)
    {
        $this->pdo = $pdo;
        $this->table = $table;
    }

    public function insert(array $records): int
    {
        if (empty($records)) {
            return 0;
        }

        $firstRecord = reset($records);
        $columns = array_keys($firstRecord);

        $placeholders = implode(', ', array_fill(0, count($columns), '?'));
        $sql = "INSERT INTO {$this->table} (" . implode(', ', $columns) . ") VALUES ({$placeholders})";

        $stmt = $this->pdo->prepare($sql);
        $inserted = 0;

        foreach ($records as $record) {
            $values = array_values($record);
            $stmt->execute($values);
            $inserted++;
        }

        return $inserted;
    }

    public function truncate(): int
    {
        $this->pdo->exec("DELETE FROM {$this->table}");
        return $this->getRowCount();
    }

    public function getRowCount(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM {$this->table}");
        return (int) $stmt->fetchColumn();
    }
}
