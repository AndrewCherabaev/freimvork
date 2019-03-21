# RouterCompiler

After retrieving routes' array from `config/routes.php`, Router compiles all routes tempates to regular expressions iteratively.
The problem is then iterator get next route path, it compiles rote to regex and mathes it with path:
```php
// Http\Router
foreach ($this->routes->all() as $route => $params) {
    $pattern = self::convertToRegExp($route, $params);
    if (preg_match($pattern, $path, $matches)) {
        $this->params = array_slice($matches, 1);

        return $route;
    }
}
```
But convertation is expencive process:
```php
// Http\Router
function convertRouteToPattern($routeChunks, $params)
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
```

So we prevent hight execution time by compiling routes to cache:
```php
// Compile routes
foreach ($routesList->all() as $route => $params) {
    $routeChunks = explode('/', $route);
    $chunks = self::convertRouteToPattern($routeChunks, $params);
    $pattern = '/^' . implode('\/', $chunks) . '\/?$/';
    // Remove 'patterns' because we already dont need it
    if (array_key_exists('patterns', $params)) {
        unset($params['patterns']);
    }
    $routes[$pattern] = $params;
}
return [
    'hash' => md5_file(CONFIG_PATH . 'routes.php'),
    'routes' => serialize($routes)
];
```
and move all code above to RouteCompiler, and save result to `framework/cache/routes.php`:
```php
function compileCacheFile()
{
    $compledRoutes = self::compileRoutes();
    $cacheFile = fopen(CACHE_PATH . 'routes.php', 'w+');

    // Some stuff here just for read-a-bi-li-ty in result file
    fwrite($cacheFile, "<?php". PHP_EOL . "return [" . PHP_EOL . "\t'routes' => '");
    fwrite($cacheFile, $compledRoutes['routes'] . "',\n\t");
    fwrite($cacheFile, "'hash' => '" . $compledRoutes['hash'] . "'\n];");
    fclose($cacheFile);
}
```

After all, rute matching time decreases in 4-5 times.