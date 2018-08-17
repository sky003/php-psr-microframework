<?php
/**
 * Initializes and builds the service container.
 *
 * This is a part of application's composition root. This definition is common
 * for all environments (dev, test, prod and etc).
 */

declare(strict_types = 1);

use League\Route\Router;
use Middlewares\Whoops;
use Symfony\Component\DependencyInjection\ContainerBuilder;

$containerBuilder = new ContainerBuilder();

$containerBuilder
    ->register('router', Router::class)
    ->setPublic(true);

$containerBuilder
    ->register('middleware.error_handler', Whoops::class)
    ->setPublic(true);

return $containerBuilder;
