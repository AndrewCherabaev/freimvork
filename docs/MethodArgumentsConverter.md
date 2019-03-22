# MethodArgumentsConverter

To solve the issue with DI in controllers' actions I decide to reflect them and check the actions' arguments types:

Generally it's look like:
```php
public static function getReflectedParams($controller, $action, $params){
    // Create reflection of {$action} method in {$controller}
    $reflection = new \ReflectionMethod($controller, $action);
    // Get all the arguments
    $reflectionParams = $reflection->getParameters();
        
    foreach ($params as $index => $param) {
        // Match all parameters in route with method arguments
        if (array_key_exists($index, $reflectionParams)) {
            // Convert it if it has defined type
            $convertedArguments[] = self::convertArgument($reflectionParams[$index]->getType(), $param);
        } else {
            // Or just return as is
            $convertedArguments[] = $param;
        }
    }
}
```
With reflector its easyly to do such DependencyInjection and get nessesary class/type inside the action.