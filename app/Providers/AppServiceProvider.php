<?php

declare(strict_types=1);

namespace App\Providers;

use EmbegeQ\Nutrisi\Contracts\Container\ContainerInterface;
use EmbegeQ\Nutrisi\Contracts\Container\ServiceProviderInterface;

class AppServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(ContainerInterface $app): void
    {
        $app->singleton(
            \EmbegeQ\Nutrisi\Contracts\Http\KernelInterface::class,
            \EmbegeQ\Nutrisi\Http\Kernel::class
        );
    }

    /**
     * {@inheritdoc}
     */
    public function boot(ContainerInterface $app): void
    {
        //
    }
}
