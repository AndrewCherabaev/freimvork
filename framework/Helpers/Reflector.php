<?php
namespace Core\Helpers;

class Reflector {
    public static function getReflectedParams($controller, $action, $params){
        $convertedArguments = [];

        $reflection = new \ReflectionMethod($controller, $action);
        $reflectionParams = $reflection->getParameters();
        
        foreach($reflectionParams as $param) {
            $argType = $param->getType();
            $convertedArguments[] = $argType->isBuiltin()
                ? self::convertBuiltIn( (string) $argType, $params[$param->name] ?? null )
                : self::convertCustom( (string) $argType, $params[$param->name] ?? null );
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
        return \method_exists($class, 'getInstance') ? $class::getInstance($param) : new $class($param);
    }
}