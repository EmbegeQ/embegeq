<?php

declare(strict_types=1);

use EmbegeQ\Nutrisi\Container\RequestContainer;
use Psr\Container\ContainerInterface;
use EmbegeQ\Nutrisi\Routing\Router;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->boot();

// Create request container
$requestContainer = new RequestContainer($app);

$psr17Factory = new Psr17Factory();
$creator = new ServerRequestCreator($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);
$request = $creator->fromGlobals();

// Inject request container as attribute
$request = $request->withAttribute(ContainerInterface::class, $requestContainer);
$requestContainer->instance(\Psr\Http\Message\ServerRequestInterface::class, $request);

$router = $app->get(Router::class);
$response = $router->dispatch($request);

// Set HTTP response code
http_response_code($response->getStatusCode());

// Send headers and body
foreach ($response->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header(sprintf('%s: %s', $name, $value), false);
    }
}
echo $response->getBody();
