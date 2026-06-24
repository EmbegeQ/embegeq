<?php

declare(strict_types=1);

return [
    'default' => $_ENV['DB_CONNECTION'] ?? 'sqlite',
    'connections' => [
        'sqlite' => [
            'driver' => 'sqlite',
            'database' => $_ENV['DB_DATABASE'] ?? __DIR__ . '/../storage/database.sqlite',
        ],
    ],
];
