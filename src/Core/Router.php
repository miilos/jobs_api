<?php

namespace Milos\JobsApi\Core;

use ReflectionClass;

class Router
{
    private array $routes;
    private Request $request;
    private Response $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function registerRoute(string $method, string $path, callable|array $action): void
    {
        $this->routes[$method][$path] = $action;
    }

    public function registerRouteAttributes(array $controllers): void
    {
        foreach ($controllers as $controller) {
            $reflectionController = new ReflectionClass($controller);

            foreach ($reflectionController->getMethods() as $method) {
                $attributes = $method->getAttributes(Route::class);

                foreach ($attributes as $attribute) {
                    $route = $attribute->newInstance();
                    $this->registerRoute($route->method, $route->path, [$controller, $method->getName()]);
                }
            }
        }
    }

    private function resolveParams(string $method, string $path): array
    {
        $requestedRoute = '/' . trim($path, '/') ?? '/';
        $routes = $this->routes[$method];
        $routeParams = [];
        $definedRoute = '';

        foreach ($routes as $route => $action) {
            // convert route to regex
            $routeRegex = preg_replace_callback('/{\w+(:([^}]+))?}/', function ($matches) {
                return isset ($matches[1]) ? '(' . $matches[2] . ')' : '([a-zA-Z0-9_-]+)';
            }, $route);
            $routeRegex = '@^' . $routeRegex . '$@';

            // check if current route matches the regex
            if (preg_match($routeRegex, $requestedRoute, $matches)) {
                // remove full match and save dynamic param values
                array_shift($matches);
                $routeParamVals = $matches;

                // get param names
                $routeParamNames = [];
                if (preg_match_all('/{(\w+)(:[^}]+)?}/', $route, $matches))
                {
                    $routeParamNames = $matches[1];
                }

                // get route as it's written in the routing function
                $definedRoute = $route;
                // combine route names and values into an associative array
                $routeParams = array_combine($routeParamNames, $routeParamVals);
            }
        }

        // [ route as defined in the router, route params ]
        return [$definedRoute, $routeParams];
    }

    public function resolve(): void
    {
        $method = $this->request->getMethod();
        $path = $this->request->getPath();

        $routeParams = $this->resolveParams($method, $path);
        $this->request->setUrlParams($routeParams[1]);

        $action = $this->routes[$method][$routeParams[0]];

        if (is_callable($action)) {
            echo call_user_func($action);
        }

        if (is_array($action)) {
            [$class, $method] = $action;

            if (class_exists($class) && method_exists($class, $method)) {
                $class = new $class();
                echo call_user_func_array([$class, $method], ['req' => $this->request, 'res' => $this->response]);
            }
        }
    }
}