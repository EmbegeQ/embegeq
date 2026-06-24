<?php

declare(strict_types=1);

use EmbegeQ\Nutrisi\Foundation\Application;

$app = new Application(dirname(__DIR__));

// Load Config
$app->loadConfiguration();

// Register All Providers (framework + app)
$app->registerConfiguredProviders();

return $app;
