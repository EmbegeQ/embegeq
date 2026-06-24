<?php

declare(strict_types=1);

use EmbegeQ\Nutrisi\Http\Request;
use EmbegeQ\Nutrisi\Contracts\Http\KernelInterface;

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$app->boot();

$kernel = $app->make(KernelInterface::class);

$response = $kernel->handle($request = Request::capture());

$kernel->send($response);

$kernel->terminate($request, $response);
