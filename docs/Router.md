# Router

Router object gets configuration from `config/routes.php` file and compiles such template as:
```php
/route_name/{:variable_key}
```
to a Regular Expression to match route path:
```php
'/^\/route_name\/(.+)\/?$/'
```

After retrieving routes' array from `config/routes.php`, Router compiles all routes tempates to regular expressions iteratively.
The problem is then iterator get next route path, it compiles rote to regex and mathes it with path:
```php
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

So we need to prevent hight execution time by compiling routes to cache

By the way, the main role of router is obvious:
```php
/**
 * $this->handler['action'] == 'ControllerName@actionName'
 */
$handler = explode('@', $this->handler['action']);
$controller = CONTROLLERS_NAMESPACE . $handler[0];
$action = $handler[1];
/**
 * Now we need to run ControllerName::actionName($request, ...$params)
 * Params desribed above in routes compiling
 */
return call_user_func_array([new $controller, $action], array_merge([$this->request], $this->params));
```
\- to get the request handler and run it.