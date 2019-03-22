<?php
namespace Core\Reflection;

class MethodArgumentsConverter {
    public static function getReflectedParams($controller, $action, $params){
        $convertedArguments = [];
        
        $reflection = self::getReflection($controller, $action);
        $reflectionParams = $reflection->getParameters();
        
        foreach ($params as $index => $param) {
            if (array_key_exists($index, $reflectionParams) && $reflectionParams[$index]->getType()) {
                $arg = $reflectionParams[$index];

                if ($arg->getType()->isBuiltin()){
                    $convertedArguments[] = self::convertBuiltIn((string)$arg->getType(), $param);
                } else {
                    $convertedArguments[] = self::convertCustom($arg->getClass()->getName(), $param);
                }
            } else {
                $convertedArguments[] = $param;
            }
        }

        return $convertedArguments;
    }

    private static function convertBuiltIn($type, $param)
    {
        switch ($type) {
            case 'integer':
            case 'int': return ((int) $param);
            case 'boolean':
            case 'bool': return ((bool) $param);
            case 'double':
            case 'float':
            case 'real': return ((float) $param);
            case 'string': return ((string) $param);
            case 'array': return ((array) $param);
        }
    }

    private static function convertCustom($class, $param)
    {
        return new $class($param);
    }

    private static function getReflection($controller, $action)
    {
        try {
            return new \ReflectionMethod($controller, $action);
        } catch (\Throwable $error) {
            throw new \Error(null, 0, (new $controller)->$action());
        }
    }
}