<?php
/**
 * Initializes and builds the service container.
 *
 * This is a part of application's composition root. This definition is common
 * for all environments (dev, test, prod and etc).
 */

declare(strict_types = 1);

use League\BooBoo\BooBoo;
use League\BooBoo\Formatter\JsonFormatter;
use League\Route\Router;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

$containerBuilder = new ContainerBuilder();

// Router
$containerBuilder
    ->register('router', Router::class)
    ->setPublic(true)
    ->addTag('core');

// Error handler
$containerBuilder
    ->register('booboo.json_formatter', JsonFormatter::class)
    ->setPublic(true)
    ->addTag('core');
$containerBuilder
    ->register('booboo', BooBoo::class)
    ->setArgument('$formatters', [new Reference('booboo.json_formatter')])
    ->setPublic(true)
    ->addTag('core');

return $containerBuilder;
