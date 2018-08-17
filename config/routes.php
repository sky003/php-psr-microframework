<?php
/**
 * Configures the router service.
 *
 * @var \Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder
 */

use League\Route\Router;

/** @var Router $router */
$router = $containerBuilder->get('router');

$router->map('GET', '/', 'App\Controller\IndexController::getIndex');

return $router;
