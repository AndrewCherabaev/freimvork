# Router

Router object gets configuration from `config/routes.php` file and compiles such template as:
```php
/route_name/{:variable_key}
```
to a Regular Expression to match route path:
```php
'/^\/route_name\/(.+)\/?$/'
```
But compiling is expensive, so we use [RouterCompiler](./RouterCompiler) to increase the speed. After that refactoring Router imports not `src/config/routes.php` but `framework/cache/routes.php`, which is actualy added to `.gitignore`. So we import and `unserialize` routes from cache and try to match route pattern with path:
```php
foreach ($this->routes->all() as $route => $params) {
    if (preg_match($route, $path, $matches)) {
        $this->params = array_slice($matches, 1);

        return $route;
    }
}
```

The main role of router is obvious:
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