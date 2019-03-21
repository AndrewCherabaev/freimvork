<?php

namespace Core;

use Core\Http\Request;
use Core\Http\Router;

class App {
    public static function run($withTimer = false) 
    {
        call_user_func_array(
            'self::run' . ($withTimer ? 'WithTimer' : 'WithoutTimer'),
            ['self::init', 'self::globals', 'self::dispatch']
        );
    }

    private static function init()
    {
        // MAIN constants
        define("DS", DIRECTORY_SEPARATOR);
        define("ROOT", getcwd() . DS);

        // ROOT constants
        define("APP_PATH", ROOT . 'src' . DS);  
        define("FRAMEWORK_PATH", ROOT . "framework" . DS);    
        define("PUBLIC_PATH", ROOT . "public" . DS);    
    
        // APP constants
        define("CONFIG_PATH", APP_PATH . "config" . DS);
        define("VIEW_PATH", APP_PATH . "Views" . DS);

        // FRAMEWORK constants
        define("CORE_PATH", FRAMEWORK_PATH . "Core" . DS);
        define("HELPER_PATH", FRAMEWORK_PATH . "Helpers" . DS);

        // REQUEST constants
        define("PLATFORM", $_REQUEST['p'] ?? 'home');
        define("CONTROLLER", $_REQUEST['c'] ?? 'Index');
        define("ACTION", $_REQUEST['a'] ?? 'index');

        // NAMESPACE constants
        define("APP_NAMESPACE", "App\\");    
        define("CONTROLLERS_NAMESPACE", APP_NAMESPACE . "Http\\Controllers\\");
    }

    private static function dispatch()
    {
        $router = new Router;

        $router->dispatch();
    }

    private static function globals()
    {
        require_once 'Globals.php';
    }

    public static function getAppConstants()
    {
        return get_defined_constants(true)['user'];
    }

    public static function getAppConstantsJson()
    {
        return json_encode(self::getAppConstants());
    }

    private static function runWithoutTimer()
    {
        foreach (func_get_args() as $callable) {
            call_user_func($callable);
        }
    }

    private static function runWithTimer()
    {
        $now = microtime(true);
        call_user_func_array('self::runWithoutTimer', func_get_args());
        error_log('Execution time: ' . (microtime(true) - $now)*1000 . ' milliseconds');
    }
}