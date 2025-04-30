<?php

use Milos\JobsApi\Core\Router;
use Milos\JobsApi\Core\Request;
use Milos\JobsApi\Core\Response;
use Milos\JobsApi\Controllers\JobController;

require_once __DIR__ . '/vendor/autoload.php';

$router = new Router(new Request(), new Response());

$router->registerRouteAttributes([
    JobController::class,
]);

$router->resolve();
