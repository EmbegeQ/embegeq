<?php

declare(strict_types=1);

/** @var \EmbegeQ\Nutrisi\Routing\Router $router */

$router->get('/', [App\Http\Controllers\WelcomeController::class, 'index']);
