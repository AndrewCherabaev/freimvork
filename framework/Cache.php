<?php
namespace Core;

class Cache {
    public static function remember($key, $data)
    {
        if (!\file_exists(APP_CACHE_PATH . $key)) {
            self::set($key, $data);
        }
        return self::get($key);
    }

    public static function forget($key)
    {
        if (\file_exists(APP_CACHE_PATH . $key)) {
            \unlink(APP_CACHE_PATH . $key);
        }
    }

    public static function get($key)
    {
        $path = explode('.', $key);
        if (!\file_exists(APP_CACHE_PATH . $path[0])) {
            return [];
        }
        $data = \file_get_contents(APP_CACHE_PATH . $path[0]);

        $content = \unserialize($data);

        if (\count($path) > 1) {
            $keystring = array_slice($path, 1);
            return array_get($content, implode('.', $keystring));
        }

        return $content;
    }

    public static function set($key, $dataProvirder) 
    {
        if (\is_callable($dataProvirder)) {
            $dataProvirder = \call_user_func($dataProvirder);
        }
        $cacheFile = fopen(APP_CACHE_PATH . $key, 'w+');
        \fwrite($cacheFile, \serialize($dataProvirder));
        return true;
    }
}