<?php
declare(strict_types = 1);

namespace Tests\Helper\Module;

use Codeception\Lib\Framework;
use Codeception\TestInterface;
use Psr\Container\ContainerInterface;
use Tests\Helper\Module\Connector\App as Connector;

/**
 * The module to perform the functional tests on this application.
 *
 * @author Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
final class App extends Framework
{
    protected $requiredFields = ['container', 'routes'];
    /**
     * @var ContainerInterface
     */
    public $container;
    /**
     * @var \App\App
     */
    public $app;

    /**
     * {@inheritdoc}
     */
    public function _initialize(): void // phpcs:ignore
    {
        $this->initializeContainer();
        $this->app = new \App\App($this->container);

        parent::_initialize();
    }

    /**
     * {@inheritdoc}
     */
    public function _before(TestInterface $test): void // phpcs:ignore
    {
        $this->client = new Connector();
        $this->client->setApp($this->app);

        parent::_before($test);
    }

    /**
     * Loads the container definition from the configuration files.
     */
    private function initializeContainer(): void
    {
        $containerFile = \codecept_root_dir().$this->config['container'];
        $testContainerFile = str_replace('.php', '', $containerFile).'-'.\App\App::ENV_TEST.'.php';
        $routesFile = \codecept_root_dir().$this->config['routes'];

        $this->container = require $containerFile;
        require_once $testContainerFile;
        require_once $routesFile;
    }
}
