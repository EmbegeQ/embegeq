<?php

declare(strict_types=1);

namespace Embegeq\Database\Migrations;

use Psr\Container\ContainerInterface;

abstract class Migration
{
    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    abstract public function up(): void;

    abstract public function down(): void;

    public function getBatchNumber(): int
    {
        return 1;
    }

    public function getConnection(): string
    {
        return 'default';
    }

    public function getName(): string
    {
        return basename(static::class);
    }
}
