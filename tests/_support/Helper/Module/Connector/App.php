<?php
declare(strict_types = 1);

namespace Tests\Helper\Module\Connector;

use Codeception\Lib\Connector\Lumen\DummyKernel;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpKernel\Client;

/**
 * The connector which Codeception module uses to make the requests to application.
 *
 * @author Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
final class App extends Client
{
    /**
     * @var \App\App
     */
    private $app;

    /**
     * App constructor.
     */
    public function __construct()
    {
        // The connector implementation doesn't use the HttpKernel.
        parent::__construct(new DummyKernel());
    }

    /**
     * @param \App\App $app
     */
    public function setApp(\App\App $app): void
    {
        $this->app = $app;
    }

    /**
     * Makes a request to application.
     *
     * @param SymfonyRequest $symfonyRequest An origin request instance.
     *
     * @return object An origin response instance.
     */
    protected function doRequest($symfonyRequest)
    {
        $psrFactory = new DiactorosFactory();
        $httpFoundationFactory = new HttpFoundationFactory();

        $psrRequest = $psrFactory->createRequest($symfonyRequest);
        $psrResponse = $this->app->handle($psrRequest);

        return $httpFoundationFactory->createResponse($psrResponse);
    }
}
