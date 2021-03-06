<?php
declare(strict_types = 1);

use App\App;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

require_once __DIR__.'/../autoload.php';

$containerBuilder = require __DIR__.'/../config/container.php';

$app = new App($containerBuilder);

$request = ServerRequestFactory::fromGlobals();
$response = $app->handle($request);

(new SapiEmitter())->emit($response);
