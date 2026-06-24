<?php

declare(strict_types=1);

namespace Embegeq\Database\Schema;

class ForeignKeyDefinition
{
    private string $column;
    private string $references = '';
    private string $on = '';
    private string $onDelete = 'RESTRICT';
    private string $onUpdate = 'RESTRICT';

    public function __construct(string $column)
    {
        $this->column = $column;
    }

    public function references(string $column): self
    {
        $this->references = $column;
        return $this;
    }

    public function on(string $table): self
    {
        $this->on = $table;
        return $this;
    }

    public function onDelete(string $action): self
    {
        $this->onDelete = $action;
        return $this;
    }

    public function onUpdate(string $action): self
    {
        $this->onUpdate = $action;
        return $this;
    }

    public function toSql(): string
    {
        return "FOREIGN KEY ({$this->column}) REFERENCES {$this->on}({$this->references}) ON DELETE {$this->onDelete} ON UPDATE {$this->onUpdate}";
    }
}
