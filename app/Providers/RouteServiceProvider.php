<?php

declare(strict_types=1);

namespace App\Providers;

use EmbegeQ\Nutrisi\Contracts\Container\ContainerInterface;
use EmbegeQ\Nutrisi\Contracts\Container\ServiceProviderInterface;
use EmbegeQ\Nutrisi\Routing\Router;

class RouteServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(ContainerInterface $app): void
    {
        $app->singleton(Router::class, function (ContainerInterface $container) {
            return new Router($container);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function boot(ContainerInterface $app): void
    {
        $router = $app->get(Router::class);

        $router->group([], function (Router $router) {
            require __DIR__ . '/../../routes/web.php';
        });

        $router->group(['prefix' => 'api'], function (Router $router) {
            require __DIR__ . '/../../routes/api.php';
        });
    }
}
