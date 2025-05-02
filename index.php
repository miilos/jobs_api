<?php

use Milos\JobsApi\Controllers\JobController;
use Milos\JobsApi\Core\Request;
use Milos\JobsApi\Core\Router;

require_once __DIR__ . '/vendor/autoload.php';

\Dotenv\Dotenv::createImmutable(__DIR__)->load();

$router = new Router(new Request());

$router->registerRouteAttributes([
    JobController::class,
]);

$router->resolve();
