<?php
namespace Core\Helpers;

class RouterCompiler extends AbstractCacheCompiler {
    protected static $FILE = 'routes.php';
    protected static $KEY = 'routes';

    protected static function compile(array $nestedRoutes = null)
    {
        $routes = [];
        $routesList = self::unnestRoutes(include (CONFIG_PATH . self::$FILE));
        foreach ($routesList as $route => $params) {
            $pattern = self::parseRoute($route, $params);
            if (\array_key_exists('patterns', $params)) {
                unset($params['patterns']);
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
        $pattern = '/^' . \implode('\/', $chunks) . '\/?$/';
        return $pattern;
    }

    private static function convertRouteToPattern($routeChunks, $params)
    {
        $converted = [];
        $currentMatches = [];
        $templates = $params['patterns'] ?? [];
        $template = '/\{\:([a-zA-Z]+)(\??)\}/';

        foreach ($routeChunks as $chunk) {
            if (!\preg_match($template, $chunk, $currentMatches)) {
                $converted[] = $chunk;
            } else {
                // Check if it has "/\{\:([a-zA-Z]+)}/" template in route description 
                $routeKey = $templates[$currentMatches[1]] ?? '.+';
                // Check if it has "/\(\??)/" template in route description 
                $questionMark = $currentMatches[2] ?? ''; 
                $converted[] = $questionMark . '(' . $routeKey .')'. $questionMark;
            }
        }
        
        return $converted;
    }
}