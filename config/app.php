<?php

declare(strict_types=1);

return [
    'name' => $_ENV['APP_NAME'] ?? 'EmbegeQ',
    'env' => $_ENV['APP_ENV'] ?? 'production',
    'debug' => (bool) ($_ENV['APP_DEBUG'] ?? false),
    'timezone' => 'UTC',
];
