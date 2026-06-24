<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use EmbegeQ\Nutrisi\Foundation\Application;

$app = new Application(dirname(__DIR__));

// Load Dotenv variables
if (file_exists(dirname(__DIR__) . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();
}

// Load Config
$app->loadConfiguration();

// Register Providers
$app->register(new App\Providers\AppServiceProvider());
$app->register(new App\Providers\RouteServiceProvider());

return $app;
