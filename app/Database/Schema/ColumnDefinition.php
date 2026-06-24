<?php

declare(strict_types=1);

namespace Embegeq\Database\Schema;

class ColumnDefinition
{
    private string $name;
    private string $type;
    private int $length = 0;
    private bool $nullable = false;
    private mixed $default = null;
    private bool $unique = false;
    private bool $primary = false;
    private bool $autoIncrement = false;
    private bool $unsigned = false;

    public function __construct(string $name, string $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    public function nullable(): self
    {
        $this->nullable = true;
        return $this;
    }

    public function default(mixed $value): self
    {
        $this->default = $value;
        return $this;
    }

    public function unique(): self
    {
        $this->unique = true;
        return $this;
    }

    public function primary(): self
    {
        $this->primary = true;
        return $this;
    }

    public function autoIncrement(): self
    {
        $this->autoIncrement = true;
        return $this;
    }

    public function unsigned(): self
    {
        $this->unsigned = true;
        return $this;
    }

    public function setLength(int $length): self
    {
        $this->length = $length;
        return $this;
    }

    public function toSql(): string
    {
        $sql = "{$this->name} {$this->type}";

        if ($this->length > 0) {
            $sql .= "({$this->length})";
        }

        if ($this->unsigned) {
            $sql .= " UNSIGNED";
        }

        if ($this->primary) {
            $sql .= " PRIMARY KEY";
        }

        if ($this->autoIncrement) {
            $sql .= " AUTOINCREMENT";
        }

        if ($this->unique && !$this->primary) {
            $sql .= " UNIQUE";
        }

        if (!$this->nullable) {
            $sql .= " NOT NULL";
        } else {
            $sql .= " NULL";
        }

        if ($this->default !== null) {
            $default = is_string($this->default) ? "'{$this->default}'" : $this->default;
            $sql .= " DEFAULT {$default}";
        }

        return $sql;
    }
}
