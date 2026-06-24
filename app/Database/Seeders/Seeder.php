<?php

declare(strict_types=1);

namespace Embegeq\Database\Seeders;

use PDO;
use Psr\Container\ContainerInterface;

abstract class Seeder
{
    protected ContainerInterface $container;
    protected PDO $pdo;

    public function __construct(ContainerInterface $container, PDO $pdo)
    {
        $this->container = $container;
        $this->pdo = $pdo;
    }

    abstract public function run(): void;

    protected function table(string $table): TableInserter
    {
        return new TableInserter($this->pdo, $table);
    }

    protected function call(string $seederClass): void
    {
        $seeder = new $seederClass($this->container, $this->pdo);
        $seeder->run();
    }
}
