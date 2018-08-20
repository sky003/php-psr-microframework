<?php
/**
 * Initializes and builds the service container.
 *
 * This is a part of application's composition root. This definition is common
 * for all environments (dev, test, prod and etc).
 */

declare(strict_types = 1);

use App\App;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
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

// Doctrine ORM
$containerBuilder
    ->register('doctrine.config', Configuration::class)
    ->setFactory([Setup::class, 'createAnnotationMetadataConfiguration'])
    ->setArguments([
        '$paths' => __DIR__.'/../src/Entity',
        '$isDevMode' => getenv('APP_ENV') !== App::ENV_PROD,
    ]);
$containerBuilder
    ->register('doctrine.em', EntityManager::class)
    ->setFactory([EntityManager::class, 'create'])
    ->setArguments([
        '$connection' => [
            'driver' => 'pdo_pgsql',
            'host' => getenv('POSTGRES_HOST'),
            'port' => getenv('POSTGRES_PORT'),
            'dbname' => getenv('POSTGRES_DB'),
            'user' => getenv('POSTGRES_USER'),
            'password' => file_get_contents(
                getenv('POSTGRES_PASSWORD_FILE')
            ),
        ],
        '$config' => new Reference('doctrine.config'),
    ]);

return $containerBuilder;
