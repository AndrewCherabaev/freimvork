<?php
namespace Freimvork\Helpers;

class RouterCompiler extends AbstractCacheCompiler {
    protected static $FILE = 'routes.php';
    protected static $KEY = 'routes';

    protected static function compile(array $nestedRoutes = null)
    {
        $routes = [];
        $routesList = self::unnestRoutes(include (CONFIG_PATH . self::$FILE));
        foreach ($routesList as $route => $params) {
            $pattern = self::parseRoute($route, $params);
            if (\array_key_exists('where', $params)) {
                unset($params['where']);
            }
            $routes[$pattern] = $params;
        }
        return $routes;
    }

    private static function unnestRoutes($routes, $parent = '')
    {
        $unnested = [];
        foreach ($routes as $route => $params) {
            if (\array_key_exists('group', $params)) {
                $unnested += self::unnestRoutes($params['group'], $route);
                unset($params['group']);
            }
            $unnested[$parent . $route] = $params;
        }

        return $unnested;
    }

    private static function parseRoute($route, $params)
    {
        $routeChunks = \explode('/', $route);
        $chunks = self::convertRouteToPattern($routeChunks, $params);
        $pattern = '/^' . \implode('\/', $chunks) . '\/?$/s';
        return $pattern;
    }

    /**
    * Further rebuild
    * compile array to one regexp like:
    * /^(
    *      (?<get_Controller_indexAction>index pattern)
    *      |
    *      (?<get_Controller_show>show pattern)
    *      |
    *      ...repeat
    * )* $/s'
    * 
    * for example, we can use 
    * /^((?<get_IndexController_index>\/users\/?)|(?<get_IndexController_show>\/users\/(?<user>\d+)\/?(?<key>.+)?\/?))$/s
    * to check /users or /users/id routes
    * 
    * after theese manipulations we can use one string instead of array in route
    *
    * we filter matches 
    * $action = array_filter($matches, function($value, $key) use ($request) {
    *     return is_string($key) && strpos($key, $request->method() . '_') === 0 && strlen($value);
    * }, ARRAY_FILTER_USE_BOTH);
    * and get $action_key: get_IndexController_show
    * 
    * then we filter attributes by 
    * $action = array_filter($matches, function($key){
    *     return is_string($key) && strpos($key, $request->method() . '_') === false;
    * }, ARRAY_FILTER_USE_KEY);
    * $handler = explode('_', $action_key), 
    *
    * and call: $handler[1] as controller, $handler[2] as action
    * 
    */
    private static function convertRouteToPattern($routeChunks, $params)
    {
        $converted = [];
        $currentMatches = [];
        $templates = $params['where'] ?? [];
        $template = '/\{\:([a-zA-Z_]+)(\??)\}/';

        foreach ($routeChunks as $chunk) {
            if (!\preg_match($template, $chunk, $currentMatches)) {
                $converted[] = $chunk;
            } else {
                // Check if it has "/\{\:([a-zA-Z]+)}/" template in route description 
                $routeKey = "?<{$currentMatches[1]}>".($templates[$currentMatches[1]] ?? '.+');
                // Check if it has "/\(\??)/" template in route description 
                $questionMark = $currentMatches[2] ?? ''; 
                $converted[] = $questionMark . '(' . $routeKey .')'. $questionMark;
            }
        }
        
        return $converted;
    }
}