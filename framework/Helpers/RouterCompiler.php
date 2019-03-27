<?php
namespace Core\Helpers;

class RouterCompiler {
    const FILE = 'routes.php';

    public static function getCompiledRoutes()
    {
        if (!file_exists(CONFIG_PATH . self::FILE)) {
            throw new \Error("File " . CONFIG_PATH . "routes.php does not exists in ");
        }

        $routesCache = include (CACHE_PATH . self::FILE);

        if (!file_exists(CACHE_PATH . self::FILE) || !array_key_exists('hash', $routesCache) || self::expiredHash($routesCache['hash'])) {
            self::compileCacheFile();
        }

        $routesCache = include (CACHE_PATH . self::FILE);

        return unserialize($routesCache['routes']);
    }

    private static function compileCacheFile()
    {
        if (file_exists(CACHE_PATH . self::FILE)) {
            unlink(CACHE_PATH . self::FILE);
        }
        $compledRoutes = self::compileRoutes();
        $compledHash = md5_file(CONFIG_PATH . self::FILE);
        $cacheFile = fopen(CACHE_PATH . self::FILE, 'w+');

        fwrite($cacheFile, "<?php". PHP_EOL . "return [" . PHP_EOL . "\t'routes' => '");
        fwrite($cacheFile, serialize($compledRoutes) . "',\n\t");
        fwrite($cacheFile, "'hash' => '" . $compledHash . "'\n];");
        fclose($cacheFile);

    }

    private static function compileRoutes(array $nestedRoutes = null)
    {
        $routes = [];
        $routesList = self::unnestRoutes(include (CONFIG_PATH . self::FILE));
        foreach ($routesList as $route => $params) {
            $pattern = self::parseRoute($route, $params);
            if (array_key_exists('patterns', $params)) {
                unset($params['patterns']);
            }
            $routes[$pattern] = $params;
        }
        return $routes;
    }

    private static function parseRoute($route, $params)
    {
        $routeChunks = explode('/', $route);
        $chunks = self::convertRouteToPattern($routeChunks, $params);
        $pattern = '/^' . implode('\/', $chunks) . '\/?$/';
        return $pattern;
    }

    private static function unnestRoutes($routes, $parent = '')
    {
        $unnested = [];
        foreach ($routes as $route => $params) {
            if (array_key_exists('group', $params)) {
                $unnested += self::unnestRoutes($params['group'], $route);
                unset($params['group']);
            }
            $unnested[$parent . $route] = $params;
        }

        return $unnested;
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

    private static function expiredHash($existingHash)
    {
        return $existingHash !== md5_file(CONFIG_PATH . self::FILE);
    }
}