<?php
namespace Core\Helpers;

abstract class AbstractCacheCompiler {
    protected static $FILE;
    protected static $KEY;

    public static function getCompiled()
    {
        if (!file_exists(CONFIG_PATH . static::$FILE)) {
            throw new \Error("File " . CONFIG_PATH . static::$FILE . " does not exists in ");
        }
        
        if (file_exists(CACHE_PATH . static::$FILE)) {
            $cache = include (CACHE_PATH . static::$FILE);
        }

        if (!file_exists(CACHE_PATH . static::$FILE) || !array_key_exists('hash', $cache) || static::expiredHash($cache['hash'])) {
            self::compileCacheFile();

            $cache = include (CACHE_PATH . static::$FILE);
        }

        return unserialize($cache[static::$KEY]);
    }

    private static function compileCacheFile()
    {
        if (file_exists(CACHE_PATH . static::$FILE)) {
            unlink(CACHE_PATH . static::$FILE);
        }
        $compled = static::compile();
        $compledHash = md5_file(CONFIG_PATH . static::$FILE);
        $cacheFile = fopen(CACHE_PATH . static::$FILE, 'w+');

        fwrite($cacheFile, "<?php". PHP_EOL . "return [" . PHP_EOL . "\t'" . static::$KEY . "' => '");
        fwrite($cacheFile, serialize($compled) . "',\n\t");
        fwrite($cacheFile, "'hash' => '" . $compledHash . "'\n];");
        fclose($cacheFile);
    }


    private static function expiredHash($existingHash)
    {
        return $existingHash !== md5_file(CONFIG_PATH . static::$FILE);
    }

    abstract protected static function compile();
}