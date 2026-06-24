<?php

declare(strict_types=1);

namespace Embegeq\Database\Concerns;

use DateTime;

trait HasSoftDeletes
{
    protected bool $useSoftDeletes = true;

    public function softDelete(): bool
    {
        $this->deleted_at = new DateTime();
        return $this->save();
    }

    public function restore(): bool
    {
        $this->deleted_at = null;
        return $this->save();
    }

    public function forceDelete(): bool
    {
        // Implement hard delete in model
        return true;
    }

    public function isDeleted(): bool
    {
        return $this->deleted_at !== null;
    }

    public function withTrashed(): self
    {
        // Remove soft delete filter
        return $this;
    }

    public function onlyTrashed(): self
    {
        // Only return soft deleted records
        return $this;
    }

    protected function save(): bool
    {
        // Implement in model that uses this trait
        return true;
    }
}
