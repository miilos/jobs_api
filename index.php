<?php

use Milos\JobsApi\Controllers\JobController;
use Milos\JobsApi\Controllers\EmployerController;
use Milos\JobsApi\Controllers\UserController;
use Milos\JobsApi\Core\Request;
use Milos\JobsApi\Core\Router;

require_once __DIR__ . '/vendor/autoload.php';

\Dotenv\Dotenv::createImmutable(__DIR__)->load();

$router = Router::getInstance();
$router->setRequest(new Request());

$controllers = [
    JobController::class,
    EmployerController::class,
    UserController::class,
];

$router->registerRouteAttributes($controllers);
$router->registerMiddlewareAttributes($controllers);

$router->resolve();
