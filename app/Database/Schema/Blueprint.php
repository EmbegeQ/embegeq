<?php

declare(strict_types=1);

namespace Embegeq\Database\Schema;

class Blueprint
{
    private string $table;
    private string $action;
    private array $columns = [];
    private array $primaryKey = [];
    private array $uniqueKeys = [];
    private array $foreignKeys = [];
    private array $indexes = [];

    public function __construct(string $table, string $action = 'create')
    {
        $this->table = $table;
        $this->action = $action;
    }

    public function id(): ColumnDefinition
    {
        return $this->unsignedBigInteger('id')->primary();
    }

    public function uuid(string $column = 'id'): ColumnDefinition
    {
        return $this->string($column, 36)->unique();
    }

    public function increments(string $column): ColumnDefinition
    {
        return $this->unsignedInteger($column)->autoIncrement();
    }

    public function bigIncrements(string $column): ColumnDefinition
    {
        return $this->unsignedBigInteger($column)->autoIncrement();
    }

    public function integer(string $column): ColumnDefinition
    {
        $col = new ColumnDefinition($column, 'INTEGER');
        $this->columns[$column] = $col;
        return $col;
    }

    public function bigInteger(string $column): ColumnDefinition
    {
        $col = new ColumnDefinition($column, 'INTEGER');
        $this->columns[$column] = $col;
        return $col;
    }

    public function unsignedInteger(string $column): ColumnDefinition
    {
        $col = new ColumnDefinition($column, 'INTEGER');
        $this->columns[$column] = $col;
        return $col;
    }

    public function unsignedBigInteger(string $column): ColumnDefinition
    {
        $col = new ColumnDefinition($column, 'INTEGER');
        $this->columns[$column] = $col;
        return $col;
    }

    public function string(string $column, int $length = 255): ColumnDefinition
    {
        $col = new ColumnDefinition($column, 'VARCHAR');
        $col->setLength($length);
        $this->columns[$column] = $col;
        return $col;
    }

    public function text(string $column): ColumnDefinition
    {
        $col = new ColumnDefinition($column, 'TEXT');
        $this->columns[$column] = $col;
        return $col;
    }

    public function longText(string $column): ColumnDefinition
    {
        $col = new ColumnDefinition($column, 'TEXT');
        $this->columns[$column] = $col;
        return $col;
    }

    public function boolean(string $column): ColumnDefinition
    {
        $col = new ColumnDefinition($column, 'BOOLEAN');
        $this->columns[$column] = $col;
        return $col;
    }

    public function json(string $column): ColumnDefinition
    {
        $col = new ColumnDefinition($column, 'JSON');
        $this->columns[$column] = $col;
        return $col;
    }

    public function timestamp(string $column): ColumnDefinition
    {
        $col = new ColumnDefinition($column, 'TIMESTAMP');
        $this->columns[$column] = $col;
        return $col;
    }

    public function timestamps(): void
    {
        $this->timestamp('created_at')->nullable();
        $this->timestamp('updated_at')->nullable();
    }

    public function softDeletes(string $column = 'deleted_at'): ColumnDefinition
    {
        return $this->timestamp($column)->nullable();
    }

    public function foreign(string $column): ForeignKeyDefinition
    {
        $fk = new ForeignKeyDefinition($column);
        $this->foreignKeys[$column] = $fk;
        return $fk;
    }

    public function unique(string|array $columns): void
    {
        $columns = is_array($columns) ? $columns : [$columns];
        $this->uniqueKeys[] = $columns;
    }

    public function index(string|array $columns): void
    {
        $columns = is_array($columns) ? $columns : [$columns];
        $this->indexes[] = $columns;
    }

    public function toSql(): string
    {
        $sql = "CREATE TABLE {$this->table} (\n";

        $parts = [];

        foreach ($this->columns as $col) {
            $parts[] = "  " . $col->toSql();
        }

        if (!empty($this->primaryKey)) {
            $parts[] = "  PRIMARY KEY (" . implode(', ', $this->primaryKey) . ")";
        }

        foreach ($this->uniqueKeys as $uk) {
            $parts[] = "  UNIQUE (" . implode(', ', $uk) . ")";
        }

        foreach ($this->foreignKeys as $fk) {
            $parts[] = "  " . $fk->toSql();
        }

        foreach ($this->indexes as $idx) {
            $parts[] = "  INDEX (" . implode(', ', $idx) . ")";
        }

        $sql .= implode(",\n", $parts);
        $sql .= "\n)";

        return $sql;
    }

    public function toAlterSql(): array
    {
        $statements = [];

        foreach ($this->columns as $col) {
            $statements[] = "ALTER TABLE {$this->table} ADD COLUMN " . $col->toSql();
        }

        return $statements;
    }

    public function primary(string|array $columns): void
    {
        $this->primaryKey = is_array($columns) ? $columns : [$columns];
    }
}
