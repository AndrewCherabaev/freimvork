<?php

namespace Core\Http;

use Core\Helpers\Container;
use Core\Helpers\MethodArgumentsConverter;
use Core\Helpers\RouterCompiler;

class Router {
    protected static $routes = [];
    protected static $params = [];
    protected static $request;
    protected static $handler;

    public function dispatch() 
    {
        self::$routes = RouterCompiler::getCompiled();
        self::$request = \request();
        self::getHandler();
        self::runHandler();
    }

    protected static function getHandler()
    {
        $route = self::matchRoutes(self::$routes, self::$request->path());

        $handler = \array_get(self::$routes, $route . '.' . self::$request->method());
        
        if (!$route || !$handler) {
            \http_response_code(404);
            echo 'not found';
            exit();
        }

        self::$handler = $handler;
        unset($route, $handler);
    }

    protected static function runHandler()
    {
        $handler = \explode('@', self::$handler);
        $controller = CONTROLLERS_NAMESPACE . $handler[0];
        $action = $handler[1];
        if (!\method_exists($controller, $action)) {
            throw new \Error("Action '{$action}' does not exists in '{$controller}'");
        }

        $params = MethodArgumentsConverter::getReflectedParams($controller, $action, \array_merge([self::$request], self::$params));

        return \call_user_func_array([new $controller, 'callAction'], [$action, $params]);
    }

    protected static function matchRoutes($routes, $path)
    {
        foreach ($routes as $route => $params) {
            $matches = [];
        
            if (\preg_match($route, $path, $matches)) {
                self::$params = \array_slice($matches, 1);

                return $route;
            }
        }

        return null;
    }
}