<?php

namespace Core\Http;

class Router {
    protected $routes = [];
    protected $params = [];
    protected $request;
    protected $handler;

    public function __construct()
    {
        $compiledRoutes = RouterCompiler::getCompiledRoutes();
        $this->routes = new \Core\Container(unserialize($compiledRoutes));
        $this->request = request();
    }

    public function dispatch() 
    {
        $this->getHandler();
        $this->runHandler();
    }

    protected function getHandler()
    {
        $path = $this->request->getPath();
        
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

        return call_user_func_array([new $controller, $action], array_merge([$this->request], $this->params));
    }

    protected function matchRoutes($path)
    {
        $matches = [];
        foreach ($this->routes->all() as $route => $params) {
            if (preg_match($route, $path, $matches)) {
                $this->params = array_slice($matches, 1);

                return $route;
            }
        }

        return null;
    }
}