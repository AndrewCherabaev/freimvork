<?php
namespace Core\Http;

class RouterCompiler {
    public static function getCompiledRoutes()
    {
        $cacheExists = file_exists(CACHE_PATH . 'routes.php');
        $routesCache = ($cacheExists) ? (include (CACHE_PATH . 'routes.php')) : [];

        if (!$cacheExists || !array_key_exists('hash', $routesCache) || !self::checkRoutesHash($routesCache['hash'])) {
            self::compileCacheFile();
            $routesCache = include (CACHE_PATH . 'routes.php');
        }
        return $routesCache['routes'];
    }

    private static function compileRoutes()
    {
        $routes = [];
        $routesList = new \Core\Container(include (CONFIG_PATH . 'routes.php') ?? []);
        foreach ($routesList->all() as $route => $params) {
            $routeChunks = explode('/', $route);
            $chunks = self::convertRouteToPattern($routeChunks, $params);
            $pattern = '/^' . implode('\/', $chunks) . '\/?$/';
            if (array_key_exists('patterns', $params)) {
                unset($params['patterns']);
            }
            $routes[$pattern] = $params;
        }
        return [
            'hash' => md5_file(CONFIG_PATH . 'routes.php'),
            'routes' => serialize($routes)
        ];
    }

    private static function compileCacheFile()
    {
        if (file_exists(CACHE_PATH . 'routes.php')) {
            unlink(CACHE_PATH . 'routes.php');
        }
        $compledRoutes = self::compileRoutes();
        $cacheFile = fopen(CACHE_PATH . 'routes.php', 'w+');

        fwrite($cacheFile, "<?php". PHP_EOL . "return [" . PHP_EOL . "\t'routes' => '");
        fwrite($cacheFile, $compledRoutes['routes'] . "',\n\t");
        fwrite($cacheFile, "'hash' => '" . $compledRoutes['hash'] . "'\n];");
        fclose($cacheFile);

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

    private static function checkRoutesHash($existingHash)
    {
        $routesHash = md5_file(CONFIG_PATH . 'routes.php');
        return ($routesHash === $existingHash);
    }
}