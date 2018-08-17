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

/** @var League\Route\Router $router */
$router = $containerBuilder->get('router');
/** @var Middlewares\Whoops $errorHandlerMiddleware */
$errorHandlerMiddleware = $containerBuilder->get('middleware.error_handler');

$router
    ->group('/api/v1', function (RouteGroup $router) {
        $router->map('GET', '/', 'App\Controller\IndexController::getIndex');
    })
    ->middleware($errorHandlerMiddleware);

return $router;
