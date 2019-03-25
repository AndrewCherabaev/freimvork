<?php
namespace Core\Reflection;

class MethodArgumentsConverter {
    public static function getReflectedParams($controller, $action, $params){
        $convertedArguments = [];
        
        $reflection = new \ReflectionMethod($controller, $action);
        $reflectionParams = $reflection->getParameters();
        
        return array_map(function($index, $param) use ($reflectionParams) {
            if (!array_key_exists($index, $reflectionParams) || !$reflectionParams[$index]->getType())
                return $param;

            $argType = $reflectionParams[$index]->getType();

            return $argType->isBuiltin()
                ? self::convertBuiltIn( (string) $argType, $param )
                : self::convertCustom( (string) $argType, $param );
        }, array_keys($params), array_values($params));
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
        if (method_exists($class, 'getInstance')) {
            return $class::getInstance($param);
        }
        return new $class($param);
    }
}