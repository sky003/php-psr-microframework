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
/** @var \App\Component\Middlewares\JwtAuthMiddleware $authMiddleware */
$authMiddleware = $container->get('middleware.jwt_auth_middleware');

$strategy = new ApplicationStrategy();
$strategy->setContainer($container);
$router->setStrategy($strategy);

$router
    ->group('/api/v1', function (RouteGroup $router) use ($authMiddleware) {
        $router->map('GET', '/', 'App\Controller\IndexController::getIndex');

        $router
            ->map('POST', '/businesses', 'App\Controller\BusinessController::create')
            ->middleware($authMiddleware);
        $router
            ->map('PUT', '/businesses/{id:number}', 'App\Controller\BusinessController::update')
            ->middleware($authMiddleware);
        $router
            ->map('PATCH', '/businesses/{id:number}', 'App\Controller\BusinessController::update')
            ->middleware($authMiddleware);
        $router->map('GET', '/businesses', 'App\Controller\BusinessController::getList');
        $router->map('POST', '/businesses/{business_id}/rating', 'App\Controller\RatingController::create');
    });

return $router;
