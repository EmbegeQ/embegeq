<?php

declare(strict_types=1);

use EmbegeQ\Nutrisi\Container\RequestContainer;
use Psr\Container\ContainerInterface;
use EmbegeQ\Nutrisi\Routing\Router;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->boot();

$psr17Factory = new Psr17Factory();
$creator = new ServerRequestCreator($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);
$router = $app->get(Router::class);

// Simulated or server-native worker loop
while ($request = get_next_request($creator)) {
    $requestContainer = new RequestContainer($app);
    $request = $request->withAttribute(ContainerInterface::class, $requestContainer);
    $requestContainer->instance(\Psr\Http\Message\ServerRequestInterface::class, $request);

    try {
        $response = $router->dispatch($request);
        emit_response($response);
    } finally {
        // Clean up Request Container
        unset($requestContainer);
        gc_collect_cycles();
    }
}

function get_next_request(ServerRequestCreator $creator): ?\Psr\Http\Message\ServerRequestInterface
{
    static $run = false;
    if ($run) {
        return null;
    }
    $run = true;
    return $creator->fromGlobals();
}

function emit_response(\Psr\Http\Message\ResponseInterface $response): void
{
    http_response_code($response->getStatusCode());
    foreach ($response->getHeaders() as $name => $values) {
        foreach ($values as $value) {
            header(sprintf('%s: %s', $name, $value), false);
        }
    }
    echo $response->getBody();
}
