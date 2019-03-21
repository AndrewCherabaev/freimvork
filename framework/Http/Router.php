<?php

namespace Core\Http;

class Router {
    protected $routes = [];
    protected $params = [];
    protected $request;
    protected $handler;

    public function __construct()
    {
        $this->routes = new \Core\Container(include (CONFIG_PATH . 'routes.php') ?? []);
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
            $pattern = self::convertToRegExp($route, $params);
            if (preg_match($pattern, $path, $matches)) {
                $this->params = array_slice($matches, 1);

                return $route;
            }
        }

        return null;
    }

    private static function convertToRegExp($route, $params)
    {
        $routeChunks = explode('/', $route);
        $chunks = self::convertRouteToPattern($routeChunks, $params);
        return '/^' . implode('\/', $chunks) . '\/?$/';
    }

    private static function convertRouteToPattern($routeChunks, $params)
    {
        $templates = $params['patterns'] ?? [];
        $template = '/\{\:([a-zA-Z]+)(\??)\}/';

        return array_map(function($chunk) use ($template, $templates) {
            $currentMatches = [];
            if (!preg_match($template, $chunk, $currentMatches)) {
                return $chunk;
            } else {
                // Check if it has "/\{\:([a-zA-Z]+)}/" template in route description 
                $routeKey = $templates[$currentMatches[1]] ?? '.+';
                // Check if it has "/\(\??)/" template in route description 
                $questionMark = $currentMatches[2] ?? ''; 
                return $questionMark . '(' . $routeKey .')'. $questionMark;
            }
        }, $routeChunks);
    }
}