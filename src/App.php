<?php
namespace App;

use League\Route\Router;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

/**
 * Application class.
 *
 * This class is responsible for instantiating, configuring, and running an application.
 *
 * @author Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
class App
{
    public const ENV_DEV = 'dev';
    public const ENV_TEST = 'test';

    /**
     * @var ContainerBuilder
     */
    private $containerBuilder;

    /**
     * App constructor.
     *
     * @param ContainerBuilder $containerBuilder
     */
    public function __construct(ContainerBuilder $containerBuilder)
    {
        $this->containerBuilder = $containerBuilder;

        $this->initializeContainer();
    }

    /**
     * Returns the application container.
     *
     * @return ContainerInterface PSR-11 compatible container.
     */
    public function getContainer(): ContainerInterface
    {
        return $this->containerBuilder;
    }

    /**
     * Runs the application.
     *
     * This method handles a request, and sends a response.
     */
    public function run(): void
    {
        /** @var ServerRequestInterface $request */
        $request = $this->getContainer()->get('request');
        /** @var Router $router */
        $router = $this->getContainer()->get('router');

        $response = $router->dispatch($request);

        (new SapiEmitter())->emit($response);
    }

    /**
     * Initializes the container.
     *
     * Here's the necessary minimum of dependencies to make this application runnable.
     */
    private function initializeContainer(): void
    {
        if (!$this->containerBuilder->has('request')) {
            $this->containerBuilder
                ->register('request', ServerRequest::class)
                ->setFactory([ServerRequestFactory::class, 'fromGlobals',])
                ->setPublic(true);
        }

        if (!$this->containerBuilder->has('router')) {
            $this->containerBuilder
                ->register('router', Router::class)
                ->setPublic(true);
        }

        $this->containerBuilder->compile();
    }
}
