<?php

declare(strict_types=1);

namespace Embegeq\Database\Seeders;

use PDO;
use Psr\Container\ContainerInterface;

class SeederRunner
{
    private ContainerInterface $container;
    private PDO $pdo;
    private string $seedersPath;

    public function __construct(ContainerInterface $container, PDO $pdo, string $seedersPath)
    {
        $this->container = $container;
        $this->pdo = $pdo;
        $this->seedersPath = $seedersPath;
    }

    public function run(?string $class = null): int
    {
        $executed = 0;

        if ($class) {
            $this->executeSeeder($class);
            $executed++;
        } else {
            $seeders = $this->getAvailableSeeders();
            foreach ($seeders as $seederClass) {
                $this->executeSeeder($seederClass);
                $executed++;
            }
        }

        return $executed;
    }

    private function executeSeeder(string $class): void
    {
        $seeder = new $class($this->container, $this->pdo);
        $seeder->run();
    }

    private function getAvailableSeeders(): array
    {
        $files = glob($this->seedersPath . '/*Seeder.php') ?: [];
        $seeders = [];

        foreach ($files as $file) {
            $class = basename($file, '.php');
            $seeders[] = 'Database\\Seeders\\' . $class;
        }

        return $seeders;
    }
}
