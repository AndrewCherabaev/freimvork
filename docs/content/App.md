# App

App is a kernel of application.

Our `index.php` requires `autoload.php` then imports `Core\App` and `run`s the application.

At first we define all global constants, that used in application:
```php
// ROOT constants
define("APP_PATH", ROOT . 'src' . DS);  
define("FRAMEWORK_PATH", __DIR__ . DS);    
define("PUBLIC_PATH", ROOT . "public" . DS);    

// APP constants
define("CONFIG_PATH", APP_PATH . "config" . DS);
define("VIEW_PATH", APP_PATH . "Views" . DS);
define("CACHE_PATH", APP_PATH . "cache" . DS);

// FRAMEWORK constants
define("CORE_PATH", FRAMEWORK_PATH . "Core" . DS);
define("HELPER_PATH", FRAMEWORK_PATH . "Helpers" . DS);
```

Not all of then are currently user, but for further work it may be needed.

After that we import [`Globals`](./Globals.md)

At the end we dispatch request through starting new [`Router`](./Router.md) 

Some meta-magic exist in such parts of code:
```php
// App::run
call_user_func_array(
    'self::run' . ($withTimer ? 'WithTimer' : 'WithoutTimer'),
    ['self::init', 'self::globals', 'self::dispatch']
);

// App::runWithTimer
$now = microtime(true);
call_user_func_array('self::runWithoutTimer', func_get_args());
error_log('Execution time: ' . (microtime(true) - $now)*1000 . ' milliseconds');

// App::self::runWithoutTimer
foreach (func_get_args() as $callable) {
    call_user_func($callable);
}
```

But, if you look into documentation, it's very obious.