<?php

declare(strict_types=1);

namespace Embegeq\Database\Concerns;

use DateTime;

trait HasTimestamps
{
    protected bool $useTimestamps = true;

    public function getTimestamps(): array
    {
        return [
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
        ];
    }

    public function timestamps(): self
    {
        $this->useTimestamps = true;
        return $this;
    }

    public function withoutTimestamps(): self
    {
        $this->useTimestamps = false;
        return $this;
    }

    public function setCreatedAtAttribute(DateTime $value): void
    {
        if ($this->useTimestamps) {
            $this->setAttribute('created_at', $value);
        }
    }

    public function setUpdatedAtAttribute(DateTime $value): void
    {
        if ($this->useTimestamps) {
            $this->setAttribute('updated_at', $value);
        }
    }

    protected function setAttribute(string $key, mixed $value): void
    {
        // Implement in model that uses this trait
    }
}
