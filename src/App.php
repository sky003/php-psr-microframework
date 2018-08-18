<?php
declare(strict_types = 1);

namespace App;

use League\Route\Router;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Whoops\Run as WhoopsRun;
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
    public function __construct(?ContainerBuilder $containerBuilder = null)
    {
        $this->containerBuilder = $containerBuilder ?? new ContainerBuilder();

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
     * This method handles a request, and sends a response. In short, this method
     * is responsible for dispatching.
     *
     * I've also thought about implementing the separated middleware queue, and
     * just make the router to be part of this queue. But seems like it will take
     * some time that I don't have right now, mainly because I can't find any
     * implementation that I can use, and I need to build and test my own
     * (see the links below).
     *
     * I decided to use "league/router" because it have friendly api, and can handle
     * the invocation of a stack of middlewares which is very useful (useful to link
     * middlewares and routes). "nikic/fast-route" package seems like doesn't provides
     * this ability.
     *
     * Anyway, current implementation meet all the requirements, and it's gonna be not so
     * hard to do some refactoring of this method in the future.
     *
     * @see https://github.com/middlewares/ideas/issues/15
     */
    public function run(): void
    {
        /** @var WhoopsRun $whoops */
        $whoops = $this->getContainer()->get('whoops');
        /** @var ServerRequestInterface $request */
        $request = $this->getContainer()->get('request');
        /** @var Router $router */
        $router = $this->getContainer()->get('router');

        $whoops->register();
        $response = $router->dispatch($request);
        (new SapiEmitter())->emit($response);
    }

    /**
     * Initializes the container.
     *
     * Here's the necessary minimum of dependencies to make this application runnable.
     * Right now the minimum necessary set of dependencies is: the request, the error handler,
     * and the router.
     */
    private function initializeContainer(): void
    {
        if (!$this->containerBuilder->has('request')) {
            $this->containerBuilder
                ->register('request', ServerRequest::class)
                ->setFactory([ServerRequestFactory::class, 'fromGlobals',])
                ->setPublic(true)
                ->addTag('core');
        }

        if (!$this->containerBuilder->has('whoops')) {
            $this->containerBuilder
                ->register('whoops', WhoopsRun::class)
                ->setPublic(true)
                ->addTag('core');
        }

        if (!$this->containerBuilder->has('router')) {
            $this->containerBuilder
                ->register('router', Router::class)
                ->setPublic(true)
                ->addTag('core');
        }

        $this->containerBuilder->compile();
    }
}
