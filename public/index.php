<?php
declare(strict_types = 1);

require_once __DIR__.'/../vendor/autoload.php';

$containerBuilder = require __DIR__.'/../config/container.php';
require __DIR__.'/../config/routes.php';

$app = new \App\App($containerBuilder);
$app->run();
