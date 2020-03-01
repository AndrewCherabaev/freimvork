<?php
namespace Freimvork\Helpers;

use Freimvork\Helpers\ConfigConverter;

abstract class AbstractCacheCompiler {
    protected static $FILE;
    protected static $KEY;

    public static function getCompiled()
    {
        if (!\file_exists(CONFIG_PATH . static::$FILE)) {
            throw new \Error("File " . CONFIG_PATH . static::$FILE . " does not exists in ");
        }


        $cacheExists = \file_exists(CACHE_PATH . static::$FILE);
        $cache = [];

        if ($cacheExists) {
            $cache = include (CACHE_PATH . static::$FILE);
        }
        
        if (!$cacheExists || !\array_key_exists('hash', $cache) || static::expiredHash($cache['hash'])) {
            self::compileCacheFile();

            $cache = include (CACHE_PATH . static::$FILE);
        }

        return \unserialize($cache[static::$KEY]);
    }

    private static function compileCacheFile()
    {
        if (\file_exists(CACHE_PATH . static::$FILE)) {
            \unlink(CACHE_PATH . static::$FILE);
        }
        $compled = static::compile();
        $compledHash = \md5_file(CONFIG_PATH . static::$FILE);
        $cacheFile = \fopen(CACHE_PATH . static::$FILE, 'w+');

        $content = static::getCompiledContent([static::$KEY, serialize($compled), $compledHash]);
        fwrite($cacheFile, $content);
        fclose($cacheFile);
    }

    private static function getCompiledContent(array $data = [])
    {
        $template = file_get_contents(DEFAULT_CONFIG_PATH . 'cache_template.php');
        return str_replace(['_key', '_data', '_hash'], $data, $template);
    }


    private static function expiredHash($existingHash)
    {
        return $existingHash !== \md5_file(CONFIG_PATH . static::$FILE);
    }

    abstract protected static function compile();
}