<?php
/**
 * This is a part of application's composition root. This definition is common
 * for all environments (dev, test, prod and etc).
 */

declare(strict_types = 1);

use App\App;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;
use League\BooBoo\BooBoo;
use League\BooBoo\Formatter\HtmlFormatter;
use League\BooBoo\Formatter\JsonFormatter;
use League\Route\Router;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorBuilder;

// Router
$container
    ->register('router', Router::class)
    ->setPublic(true)
    ->addTag('core');

// Error handler
$container
    ->register('booboo.json_formatter', JsonFormatter::class)
    ->setPublic(true)
    ->addTag('core');
$container
    ->register('booboo.html_formatter', HtmlFormatter::class)
    ->setPublic(true)
    ->addTag('core');
$container
    ->register('booboo', BooBoo::class)
    ->setArgument('$formatters', [new Reference('booboo.json_formatter')])
    ->setPublic(true)
    ->addTag('core');

// Doctrine ORM
$container
    ->register('doctrine.config', Configuration::class)
    ->setFactory([Setup::class, 'createAnnotationMetadataConfiguration'])
    ->setArguments([
        '$paths' => [
            __DIR__.'/../src/Entity',
        ],
        '$isDevMode' => getenv('APP_ENV') !== App::ENV_PROD,
        '$proxyDir' => null,
        '$cache' => null,
        '$useSimpleAnnotationReader' => false,
    ]);
$container
    ->register('doctrine.entity_manager', EntityManager::class)
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

// Serializer
$container
    ->register('serializer.normalizer.object_normalizer', ObjectNormalizer::class);
$container
    ->register('serializer.normalizer.date_time_normalizer', DateTimeNormalizer::class);
$container
    ->register('serializer.encoder.json_encoder', JsonEncoder::class);
$container
    ->register('serializer', Serializer::class)
    ->setArguments([
        '$normalizers' => [
            new Reference('serializer.normalizer.date_time_normalizer'),
            new Reference('serializer.normalizer.object_normalizer'),
        ],
        '$encoders' => [
            new Reference('serializer.encoder.json_encoder'),
        ],
    ]);

// Validator
$container
    ->register('validator.builder', ValidatorBuilder::class)
    ->setFactory([Validation::class, 'createValidatorBuilder'])
    ->addMethodCall('enableAnnotationMapping');
$container
    ->register('validator', RecursiveValidator::class)
    ->setFactory([new Reference('validator.builder'), 'getValidator']);


// Set the aliases for the proper type-hints.
$container->setAlias(EntityManagerInterface::class, new Alias('doctrine.entity_manager'));
$container->setAlias(SerializerInterface::class, new Alias('serializer'));
$container->setAlias(ValidatorInterface::class, new Alias('validator'));
