<?php

declare(strict_types=1);

use EmbegeQ\Nutrisi\Http\Request;
use EmbegeQ\Nutrisi\Contracts\Http\KernelInterface;

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->boot();

$kernel = $app->make(KernelInterface::class);

// Simulated or server-native worker loop
while ($request = get_next_request()) {
    try {
        $response = $kernel->handle($request);
        $kernel->send($response);
        $kernel->terminate($request, $response);
    } catch (\Throwable $e) {
        // Log or handle the exception
    }
}

function get_next_request(): ?\Psr\Http\Message\ServerRequestInterface
{
    static $run = false;
    if ($run) {
        return null;
    }
    $run = true;
    return Request::capture();
}
