<?php
/**
 * Initializes and builds the service container.
 */

declare(strict_types = 1);

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

$container = new ContainerBuilder();

$loader = new PhpFileLoader($container, new FileLocator(__DIR__));
$loader->load('container-common.php');
$loader->load('routes.php');

// Register the application classes as services.
// All of them available for autoloading and autoconfiguration.
$definition = new Definition();
$definition
    ->setAutowired(true)
    ->setAutoconfigured(true)
    ->setPublic(true);
$loader->registerClasses(
    $definition,
    'App\\',
    '../src/*',
    '../src/{App.php,Entity,Migrations,Tests}'
);

return $container;
