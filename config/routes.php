<?php
/**
 * Configures the router service.
 *
 * This is also a good place to add the middlewares matched to the router,
 * a router group, or a specific route.
 *
 * @var \Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder
 */

declare(strict_types = 1);

use League\Route\RouteGroup;
use League\Route\Strategy\ApplicationStrategy;

/** @var \League\Route\Router $router */
$router = $container->get('router');

$strategy = new ApplicationStrategy();
$strategy->setContainer($container);
$router->setStrategy($strategy);

$router
    ->group('/api/v1', function (RouteGroup $router) {
        $router->map('GET', '/', 'App\Controller\IndexController::getIndex');

        $router->map('POST', '/businesses', 'App\Controller\BusinessController::create');
        $router->map('PUT', '/businesses/{id:number}', 'App\Controller\BusinessController::update');
        $router->map('PATCH', '/businesses/{id:number}', 'App\Controller\BusinessController::update');
    });

return $router;
