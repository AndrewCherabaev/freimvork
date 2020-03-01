<?php
/**
 * Author: me
 * 
 * Dispatch {@see Core\Http\Request} to a proper {@see Core\Http\Controller} inherited class
 * @package Core\Http\Router
 */
namespace Freimvork\Http;

use Freimvork\Helpers\Container;
use Freimvork\Helpers\Reflector;
use Freimvork\Helpers\RouterCompiler;
/**
 * Dispatch request to a proper handler
 * 
 * @package Router
 */
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

        $handler = \array_get(self::$routes, $route . ':' . self::$request->method(), null);
        
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

        $params = Reflector::getReflectedParams($controller, $action, self::$params);

        return \call_user_func_array([new $controller, 'callAction'], [$action, $params]);
    }

    protected static function matchRoutes($routes, $path)
    {
        foreach ($routes as $route => $_) {
            $matches = [];
        
            if (\preg_match($route, $path, $matches)) {
                self::$params = array_filter($matches, function($key) { return is_string($key); }, ARRAY_FILTER_USE_KEY);

                return $route;
            }
        }

        return null;
    }
}