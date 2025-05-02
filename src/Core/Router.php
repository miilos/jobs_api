<?php

namespace Milos\JobsApi\Core;

use Milos\JobsApi\Core\Responses\JSONResponse;
use Milos\JobsApi\Core\Exceptions\APIException;
use ReflectionClass;

class Router
{
    private array $routes;
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
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
            // /jobs/{id} will be transformed into /jobs/@^(regex for letters, numbers and characters)$@
            // if the route contains type specifications, like \d+ for numbers only,
            // it will be transformed from /jobs/{id:\d+} to /jobs/@^(\d+)$@
            $routeRegex = preg_replace_callback('/{\w+(:([^}]+))?}/', function ($matches) {
                return isset ($matches[1]) ? '(' . $matches[2] . ')' : '([a-zA-Z0-9_-]+)';
            }, $route);
            $routeRegex = '@^' . $routeRegex . '$@';

            // check if current route matches the regex
            if (preg_match($routeRegex, $requestedRoute, $matches)) {
                // matches[0] is the full match, only the values of the params are needed
                // they're in the rest of the array, because preg_match stores each separate match
                // (part of the string that matches the part of the regex enclosed in ())
                // in a separate array element
                array_shift($matches);
                $routeParamVals = $matches;

                // because (\w+) is enclosed in brackets, the part of the url before the dynamic parameter
                // will match that part of the regex and be places in matches[1]
                $routeParamNames = [];
                if (preg_match_all('/{(\w+)(:[^}]+)?}/', $route, $matches))
                {
                    $routeParamNames = $matches[1];
                }

                // get route as it's written in the routing function
                // so that the appropriate function can be found in the routes array later
                $definedRoute = $route;
                // combine route names and values into an associative array to add to request
                $routeParams = array_combine($routeParamNames, $routeParamVals);
            }
        }

        // [ route as defined in the router, route params ]
        return [$definedRoute, $routeParams];
    }

    public function resolve(): void
    {
        try {
            $method = $this->request->getMethod();
            $path = $this->request->getPath();
            $routeParams = $this->resolveParams($method, $path);
            $this->request->setUrlParams($routeParams[1]);

            if (!array_key_exists($routeParams[0], $this->routes[$method])) {
                throw new APIException('route not found!', 404);
            }

            $action = $this->routes[$method][$routeParams[0]];
            [$class, $method] = $action;

            $response = null;

            if (class_exists($class) && method_exists($class, $method)) {
                $class = new $class();
                $response = call_user_func_array([$class, $method], ['req' => $this->request]);
                $response->statusCode(200);
            }
        }
        catch (APIException $apiEx) {
            $response = new JSONResponse([
                'status' => 'fail',
                'message' => $apiEx->getMessage()
            ]);
            $response->statusCode($apiEx->getStatusCode());
        }
        catch (\PDOException $pdoEx) {
            $response = new JSONResponse([
                'status' => 'error',
                'message' => 'greska pri povezivanju sa bazom!',
                'details' => $pdoEx->getMessage()
            ]);
            $response->statusCode(500);
        }
        catch (\Throwable $t) {
            $response = new JSONResponse([
                'status' => 'error',
                'message' => 'nesto ne radi!',
                'details' => $t->getMessage()
            ]);
            $response->statusCode(500);
        }
        finally {
            echo $response->send();
        }
    }
}