<?php

declare(strict_types=1);

namespace Embegeq\Database;

use PDO;
use PDOStatement;

class QueryBuilder
{
    private PDO $pdo;
    private string $table;
    private array $select = ['*'];
    private array $where = [];
    private array $bindings = [];
    private array $joins = [];
    private array $orderBy = [];
    private int $limit = 0;
    private int $offset = 0;

    public function __construct(PDO $pdo, string $table)
    {
        $this->pdo = $pdo;
        $this->table = $table;
    }

    public function select(string|array $columns = ['*']): self
    {
        $this->select = is_array($columns) ? $columns : [$columns];
        return $this;
    }

    public function where(string $column, string $operator, mixed $value): self
    {
        $this->where[] = "{$column} {$operator} ?";
        $this->bindings[] = $value;
        return $this;
    }

    public function whereIn(string $column, array $values): self
    {
        $placeholders = implode(', ', array_fill(0, count($values), '?'));
        $this->where[] = "{$column} IN ({$placeholders})";
        $this->bindings = array_merge($this->bindings, $values);
        return $this;
    }

    public function join(string $table, string $on): self
    {
        $this->joins[] = "JOIN {$table} ON {$on}";
        return $this;
    }

    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->orderBy[] = "{$column} {$direction}";
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    public function get(): array
    {
        $sql = $this->toSql();
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bindings);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function first(): ?array
    {
        $this->limit = 1;
        $results = $this->get();
        return $results[0] ?? null;
    }

    public function count(): int
    {
        $sql = "SELECT COUNT(*) FROM {$this->table}";

        if (!empty($this->where)) {
            $sql .= " WHERE " . implode(" AND ", $this->where);
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bindings);
        return (int) $stmt->fetchColumn();
    }

    public function insert(array $data): bool
    {
        $columns = array_keys($data);
        $placeholders = implode(', ', array_fill(0, count($columns), '?'));
        $sql = "INSERT INTO {$this->table} (" . implode(', ', $columns) . ") VALUES ({$placeholders})";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array_values($data));
    }

    public function update(array $data): int
    {
        $updates = array_map(fn($col) => "{$col} = ?", array_keys($data));
        $sql = "UPDATE {$this->table} SET " . implode(', ', $updates);

        if (!empty($this->where)) {
            $sql .= " WHERE " . implode(" AND ", $this->where);
        }

        $values = array_merge(array_values($data), $this->bindings);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($values);
        return $stmt->rowCount();
    }

    public function delete(): int
    {
        $sql = "DELETE FROM {$this->table}";

        if (!empty($this->where)) {
            $sql .= " WHERE " . implode(" AND ", $this->where);
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bindings);
        return $stmt->rowCount();
    }

    private function toSql(): string
    {
        $sql = "SELECT " . implode(', ', $this->select) . " FROM {$this->table}";

        if (!empty($this->joins)) {
            $sql .= " " . implode(" ", $this->joins);
        }

        if (!empty($this->where)) {
            $sql .= " WHERE " . implode(" AND ", $this->where);
        }

        if (!empty($this->orderBy)) {
            $sql .= " ORDER BY " . implode(', ', $this->orderBy);
        }

        if ($this->limit > 0) {
            $sql .= " LIMIT {$this->limit}";
        }

        if ($this->offset > 0) {
            $sql .= " OFFSET {$this->offset}";
        }

        return $sql;
    }
}
