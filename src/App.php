<?php
declare(strict_types = 1);

namespace App;

use League\BooBoo\BooBoo;
use League\BooBoo\Formatter\JsonFormatter;
use League\Route\Http\Exception as RouterHttpErrorException;
use League\Route\Router;
use Middlewares\Utils\HttpErrorException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Zend\Diactoros\Response;

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
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     *
     * @see https://github.com/middlewares/ideas/issues/15
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var BooBoo $booBoo */
        $booBoo = $this->getContainer()->get('booboo');
        /** @var Router $router */
        $router = $this->getContainer()->get('router');

        $booBoo->register();

        try {
            $response = $router->dispatch($request);
        } catch (HttpErrorException | RouterHttpErrorException $exception) {
            return $this->handleError($exception);
        }

        return $response;
    }

    /**
     * Initializes the container.
     *
     * Here's the necessary minimum of dependencies to make this application runnable.
     * Right now the minimum necessary set of dependencies is: the error handler,
     * and the router.
     */
    private function initializeContainer(): void
    {
        if (!$this->containerBuilder->has('booboo')) {
            $this->containerBuilder
                ->register('booboo.json_formatter', JsonFormatter::class)
                ->setPublic(true)
                ->addTag('core');
            $this->containerBuilder
                ->register('booboo', BooBoo::class)
                ->setArgument('$formatters', [new Reference('booboo.json_formatter')])
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

    /**
     * Handles the http errors.
     *
     * @param \Exception $exception
     *
     * @return ResponseInterface
     */
    private function handleError(\Exception $exception): ResponseInterface
    {
        if ($exception instanceof RouterHttpErrorException
            || $exception instanceof HttpErrorException) {
            /** @var JsonFormatter $jsonFormatter */
            $jsonFormatter = $this->getContainer()->get('booboo.json_formatter');

            $response = new Response();
            $response->getBody()->write($jsonFormatter->format($exception));

            $statusCode = $exception instanceof HttpErrorException
                ? $exception->getCode()
                : $exception->getStatusCode();

            return $response
                ->withStatus($statusCode)
                ->withHeader('Content-Type', 'application/json');
        }

        throw $exception;
    }
}
