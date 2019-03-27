<?php
namespace Core\Helpers;

class ConfigConverter {
    public static function getDatabaseConfig()
    {
        $dbDefaults = self::getConfig('database_defaults.php', DEFAULT_CONFIG_PATH);
        $dbConfig = self::getConfig('database.php');
        
        $driver = array_get($dbConfig, 'driver', array_get($dbDefaults, 'driver'));
        if (!in_array($driver, \PDO::getAvailableDrivers())) {
            throw new \Error("Driver '{$driver}' not found or not installed");
        }

        foreach ($dbDefaults as $property => $value) {
            $dbConfig[$property] = $dbConfig[$property] ?? $value;
        }

        return $dbConfig;
    }

    public static function getViewConfig()
    {
        return self::getConfig('view.php');
    }

    protected static function getConfig($filename, $CONFIG_PATH = CONFIG_PATH)
    {
        $config = $CONFIG_PATH . $filename;

        if (file_exists($config)) {
            return include $config;
        }

        return [];
    }
}