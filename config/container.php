<?php
/**
 * Initializes and builds the service container.
 *
 * This is a part of application's composition root. This definition is common
 * for all environments (dev, test, prod and etc).
 */

declare(strict_types = 1);

use League\Route\Router;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Run as WhoopsRun;

$containerBuilder = new ContainerBuilder();

// Router
$containerBuilder
    ->register('router', Router::class)
    ->setPublic(true)
    ->addTag('core');

// Error handler
$containerBuilder
    ->register('whoops', WhoopsRun::class)
    ->addMethodCall('pushHandler', [new Reference('whoops.handler.json_response_handler')])
    ->setPublic(true)
    ->addTag('core')
    ->addTag('whoops');
$containerBuilder
    ->register('whoops.handler.json_response_handler', JsonResponseHandler::class)
    ->setPublic(true)
    ->addTag('whoops');

return $containerBuilder;
