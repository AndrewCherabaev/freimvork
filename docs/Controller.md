# Controller

Controller is described as request-handler: it get input data and returns result of computations
Main method is `render()` which gets the name if temnplate and renders it with given data:
```php
return self::createCompiler()($this->buildViewPath($view), $data);
```
Going further, it gets `buildViewPath()` function to compile string `'dirname:filename'` to full filename string like `VIEW_PATH . 'dirname/filename.php'` (about VIEW_PATH global constant you can see in [App](./App.md) document).

After that is going template compiling: 
```php
return function() {
    ob_start();
    extract(func_get_arg(1));
    include func_get_arg(0);
    return ob_get_flush();
};
```
\- that means creating a buffer context, unpacking all given variables from data array:
```php
// This expression...
extract(['a' => 1, 'b' => 2])
// ... produces ...
$a = 1;
$b = 2;
// ... in current buffer context
```
\- than compiling included view file in that context with that variables.

As a result we have a string which contains compiled HTML markdown.
