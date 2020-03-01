<?php

namespace Freimvork;

use Freimvork\Http\Request; 
use Freimvork\Http\Router;
use Freimvork\ErrorHandler;

/**
 * Main app class
 * 
 * Creates the application, registers global variablesa and dispatch
 * request to a propper handler
 * 
 * @package \Core\App
 */
class App {
    /**
     * Run
     * 
     * Main method that initializes application, registers globals {@see \Core\App::globals()}
     * 
     * Initialisig order:
     *  - defines all global constants {@see Core\App::init()}
     *  - register all global function {@see Core\App::globals()}
     *  - dispatches request to router {@see Core\App::dispatch()} {@see Core\Http\Router::dispatch()}
     * 
     * If dispatching is failed, sends HTTP 500 error and renders error log page {@see Core\App::dumpErrorLog()} {@see Core\ErrorHandler}
     * 
     * @static
     * 
     * @return void
     */
    public static function run() 
    {
        self::init(); 
        self::globals(); 
        self::dispatch();
    }

    /**
     * @static
     *
     * @return void
     */
    private static function init()
    {
        // MAIN constants
        define("DS", DIRECTORY_SEPARATOR);
        define("ROOT", getcwd() . DS);

        // ROOT constants
        define("APP_PATH", ROOT . 'src' . DS);  
        define("FRAMEWORK_PATH", __DIR__ . DS);   
        define("PUBLIC_PATH", ROOT . "public" . DS);    
    
        // APP constants
        define("CONFIG_PATH", APP_PATH . "config" . DS);
        define("VIEW_PATH", APP_PATH . "Views" . DS);
        define("CACHE_PATH", APP_PATH . "cache" . DS);
        if (!file_exists(CACHE_PATH) && !is_dir(CACHE_PATH)) {
            mkdir(CACHE_PATH);
        }

        // FRAMEWORK constants
        define("CORE_PATH", FRAMEWORK_PATH . "Core" . DS);
        define("HELPER_PATH", FRAMEWORK_PATH . "Helpers" . DS);
        define("DEFAULT_CONFIG_PATH", FRAMEWORK_PATH . "config" . DS);

        // NAMESPACE constants
        define("APP_NAMESPACE", "App\\");    
        define("CONTROLLERS_NAMESPACE", APP_NAMESPACE . "Http\\Controllers\\");
        session_start();
    }

    /**
     * @static
     *
     * @return void
     */
    private static function dispatch()
    {
        try {
            Router::dispatch();
        } catch (\Throwable $error) {
            \http_response_code(500);
            self::dumpErrorLog($error);
        }
    }

    /**
     * @static
     *
     * @return void
     */
    private static function globals()
    {
        include 'Globals.php';
    }

    /**
     * Returns user-defined constants
     * 
     * Usage: \Core\App::getAppConstants()
     * 
     * @static
     *
     * @return void
     */
    public static function getAppConstants()
    {
        return \get_defined_constants(true)['user'];
    }

    /**
     * Returns user-defined constants as JSON-object
     * 
     * Usage: \Core\App::getAppConstantsJson()
     * 
     * @static
     *
     * @return void
     */
    public static function getAppConstantsJson()
    {
        return \json_encode(self::getAppConstants());
    }

    /**
     * @static
     *
     * @param \Error $error
     * @return void
     */
    private static function dumpErrorLog($error)
    {
        $errorPrinter = new ErrorHandler($error);
        $errorPrinter
            ->printError()
            ->printTrace();
    }
}