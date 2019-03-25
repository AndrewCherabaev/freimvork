<?php

namespace Core\Http;

use Core\Reflection\MethodArgumentsConverter;

class Router {
    protected $routes = [];
    protected $params = [];
    protected $request;
    protected $handler;

    public function __construct()
    {
        $compiledRoutes = RouterCompiler::getCompiledRoutes();
        $this->routes = new \Core\Container($compiledRoutes);
        $this->request = request();
    }

    public function dispatch() 
    {
        $this->getHandler();
        $this->runHandler();
    }

    protected function getHandler()
    {
        $path = $this->request->path();
        
        $route = $this->matchRoutes($path);

        $handler = $this->routes->get($route);
        
        if (!$route || !$handler) {
            http_response_code(404);
            echo 'not found';
            exit();
        }

        $this->handler = $handler;
    }

    protected function runHandler()
    {
        $handler = explode('@', $this->handler['action']);
        $controller = CONTROLLERS_NAMESPACE . $handler[0];
        $action = $handler[1];
        if (!method_exists(new $controller, $action)) {
            throw new \Error("Action '{$action}' does not exists in '{$controller}'");
        }

        $params = MethodArgumentsConverter::getReflectedParams($controller, $action, array_merge([$this->request], $this->params));

        return call_user_func_array([new $controller, 'callAction'], [$action, $params]);
    }

    protected function matchRoutes($path)
    {
        foreach ($this->routes->all() as $route => $params) {
            $matches = [];
        
            if (preg_match($route, $path, $matches)) {
                $this->params = array_slice($matches, 1);

                return $route;
            }
        }

        return null;
    }
}